<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderItem as OrderItemResource;
use App\Http\Controllers\Controller;
use App\Models\OrderItemVariation;
use App\Models\OrderItemVariationValue;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{

    public function updateItemType(Request $request, $id)
    {
        OrderItem::find($id)->update(["item_type" => $request->item_type]);
        if ($request->item_type == "Factory") {
            OrderItem::find($id)->update(["supplier" => null, "embellishment_supplier" => null]);
        }
        return new OrderItemResource(OrderItem::find($id));
    }

    public function updateSupplier(Request $request, $itemId)
    {
        OrderItem::find($itemId)->update(["supplier" => $request->supplier]);
        return new OrderItemResource(OrderItem::find($itemId));
    }

    public function updateSupplierStatus(Request $request, $itemId)
    {
        OrderItem::find($itemId)->update(["supplier_status" => $request->supplier_status]);
        if ($request->supplier_status == 'Received') {
            $order_item_variations = OrderItemVariation::where('order_item_id', $itemId)->get();
            foreach ($order_item_variations as $order_item_variation) {
                $order_item_variation_values = OrderItemVariationValue::where('order_item_variation_id', $order_item_variation->id)->get();
                foreach ($order_item_variation_values as $order_item_variation_value) {
                    $qty = $order_item_variation_value->qty;
                    $order_item_variation_value->delivered_qty = $qty;
                    $order_item_variation_value->save();
                }
            }
        }
        return new OrderItemResource(OrderItem::find($itemId));
    }

    public function updateEmbellishmentSupplier(Request $request, $itemId)
    {
        OrderItem::find($itemId)->update(["embellishment_supplier" => $request->embellishment_supplier]);
        return new OrderItemResource(OrderItem::find($itemId));
    }

    public function updateEmbellishmentStatus(Request $request, $itemId)
    {
        OrderItem::find($itemId)->update(["embellishment_status" => $request->embellishment_status]);
        return new OrderItemResource(OrderItem::find($itemId));
    }

    public function updateFactoryStatus(Request $request, $itemId)
    {
        OrderItem::find($itemId)->update(["factory_status" => $request->factory_status]);

        if ($request->factory_status == 'Received') {
            $order_item_variations = OrderItemVariation::where('order_item_id', $itemId)->get();
            foreach ($order_item_variations as $order_item_variation) {
                $order_item_variation_values = OrderItemVariationValue::where('order_item_variation_id', $order_item_variation->id)->get();
                foreach ($order_item_variation_values as $order_item_variation_value) {
                    $qty = $order_item_variation_value->qty;
                    $order_item_variation_value->delivered_qty = $qty;
                    $order_item_variation_value->save();
                }
            }
        }

        return new OrderItemResource(OrderItem::find($itemId));
    }

    public function updateDeliveredQty(Request $request, $itemId)
    {
        OrderItem::find($itemId)->update(["delivered_qty" => $request->delivered_qty]);
        return new OrderItemResource(OrderItem::find($itemId));
    }

}
