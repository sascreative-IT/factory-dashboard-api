<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderCommentRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\Order as OrderResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\OrderComments;
use App\Models\OrderDeliveryHistory;
use App\Notifications\EnabledForWarehouseNotification;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Response;

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
        $order = Order::where("merch_order_id", $merchOrderId)->first();
        if ($order->po == "") {
            return response()->json(['data' => "The PO can not be empty."], Response::HTTP_BAD_REQUEST);
        }

        if ($order->delivery_date == "") {
            return response()->json(['data' => "The delivery date can not be empty."], Response::HTTP_BAD_REQUEST);
        }

        if ($order->items) {
            foreach ($order->items as $index => $item) {
                if ($item->item_type == "") {
                    return response()->json(
                        ['data' => "The item type can not be empty for $item->product_code."],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                if ($item->item_type == "Local") {
                    if ($item->supplier == "") {
                        return response()->json(
                            ['data' => "The item supplier can not be empty for $item->product_code."],
                            Response::HTTP_BAD_REQUEST
                        );
                    }

                    if ($item->embellishment_supplier == "") {
                        return response()->json(
                            ['data' => "The item embellishment supplier can not be empty for $item->product_code."],
                            Response::HTTP_BAD_REQUEST
                        );
                    }
                }
            }
        }

        $enabled_for_warehouse = $request->enabled_for_warehouse;
        $order->update(
            ['enabled_for_warehouse' => $enabled_for_warehouse]
        );
        if ($enabled_for_warehouse == 'Yes') {
            if (config('mail.order_enabled_notification') == 'Yes') {
                if (auth()->user()) {
                    $user = auth()->user();
                    $user->notify(new EnabledForWarehouseNotification(
                        [
                            'merch_order_id' => $order->merch_order_id,
                            'name' => $user->name,
                            'email' => $user->email
                        ]));
                }
            }
        }
        return new OrderResource($order);
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
        $order->update(
            [
                'delivery_status' => $request->delivery_status,
                'store_name' => $request->store_name,
                'no_of_boxes_delivered' => $request->no_of_boxes_delivered,
            ]
        );
        $order->save();

        OrderDeliveryHistory::create(
            [
                'order_id' => $order->id,
                'delivery_date' => $order->delivery_date,
                'delivery_status' => $request->delivery_status,
                'store_name' => $request->store_name,
                'no_of_boxes_delivered' => $request->no_of_boxes_delivered,

            ]
        );

        return new OrderResource($order);
    }

    public function updateOrderStatus(Request $request, $merchOrderId)
    {
        $order = Order::where("merch_order_id", $merchOrderId)->first();
        $order->update(['order_status' => $request->order_status]);
        $order->save();
        return new OrderResource($order);
    }

    public function updateDeliveryDate(Request $request, $merchOrderId)
    {
        $order = Order::where("merch_order_id", $merchOrderId)->first();
        $order->update(['delivery_date' => $request->delivery_date]);
        $order->save();
        return new OrderResource($order);
    }

}
