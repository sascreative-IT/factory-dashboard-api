<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderCommentRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\Order as OrderResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $per_page = 20;
        return new OrderCollection(Order::paginate($per_page));
    }

    public function show($merchOrderId)
    {
        return new OrderResource(Order::with('items','comments')->where("merch_order_id", $merchOrderId)->first());
    }


    public function updateStatus(OrderStatusRequest $request, $merchOrderId)
    {
        $order = Order::with('items')->where("merch_order_id", $merchOrderId)->first();
        if ($order->status_at_factory == "Completed-Shipped") {
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
        $orderWithComments = Order::with('items','comments')->where("merch_order_id", $merchOrderId)->first();
        return new OrderResource($orderWithComments);
    }

}
