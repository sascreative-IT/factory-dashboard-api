<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderCommentRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\Order as OrderResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\OrderComments;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;

class OrderController extends Controller
{
    public function index()
    {
        $per_page = 20;
        return new OrderCollection(Order::paginate($per_page));
    }

    public function show($merchOrderId)
    {
        return new OrderResource(Order::with('items', 'comments')->where("merch_order_id", $merchOrderId)->first());
    }


    public function updateStatus(OrderStatusRequest $request, $merchOrderId)
    {
        $order = Order::with('items')->where("merch_order_id", $merchOrderId)->first();
        if ($order->status_at_factory != "Completed") {
            $order->update(['status_at_factory' => $request->status]);
            $order->save();
        }
        return new OrderResource($order);
    }

    public function addComment(OrderCommentRequest $request)
    {
        $merchOrderId = $request->orderId;
        $order = Order::where("merch_order_id", $request->orderId)->first();
        $order->comments()->create(
            [
                'comments' => $request->comments,
                'added_by_name' => $request->added_by_name,
                'added_by_email' => $request->added_by_email,
            ]
        );
        $order->save();
        $orderId = $order->id;
        $comment = OrderComments::where("order_id", $orderId)->latest()->first();
        return new CommentResource($comment);
    }

    public function update(Request $request, $merchOrderId)
    {
        $enabled_for_warehouse = $request->enabled_for_warehouse;
        Order::where("merch_order_id", $merchOrderId)->first()->update(
            ['enabled_for_warehouse' => $enabled_for_warehouse]
        );
        return new OrderResource(Order::where("merch_order_id", $merchOrderId)->first());
    }

    public function searchWarehouseOrder($merchOrderId)
    {
        $orderInfo = Order::with('items', 'comments')
            ->where("enabled_for_warehouse", "Yes")
            ->where("merch_order_id", $merchOrderId)->first();
        return new OrderResource($orderInfo);
    }

    public function updateDeliveryStatus(Request $request, $merchOrderId)
    {
        $order = Order::where("merch_order_id", $merchOrderId)->first();
        $order->update(['delivery_status' => $request->delivery_status, 'store_name' => $request->store_name]);
        $order->save();
        return new OrderResource($order);
    }

    public function updateOrderStatus(Request $request, $merchOrderId)
    {
        $order = Order::where("merch_order_id", $merchOrderId)->first();
        $order->update(['order_status' => $request->order_status]);
        $order->save();
        return new OrderResource($order);
    }

    public function updateDeliveryDate(Request $request, $merchOrderId){
        $order = Order::where("merch_order_id", $merchOrderId)->first();
        $order->update(['delivery_date' => $request->delivery_date]);
        $order->save();
        return new OrderResource($order);
    }

}
