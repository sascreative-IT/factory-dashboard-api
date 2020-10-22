<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderItem as OrderItemResource;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{

    public function updateItemType(Request $request, $id)
    {
        OrderItem::find($id)->update(["item_type" => $request->item_type]);
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
}
