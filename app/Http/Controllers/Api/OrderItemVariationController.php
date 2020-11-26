<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderItemVariationValue as OrderItemVariationValueResource;
use App\Http\Controllers\Controller;
use App\Models\OrderItemVariationValue;
use Illuminate\Http\Request;

class OrderItemVariationController extends Controller
{
    public function updateDeliveredQty(Request $request, $variationValueId)
    {
        OrderItemVariationValue::find($variationValueId)->update(["delivered_qty" => $request->delivered_qty]);
        return new OrderItemVariationValueResource(OrderItemVariationValue::find($variationValueId));
    }
}
