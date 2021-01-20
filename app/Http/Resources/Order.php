<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $orderItems = [];

        foreach ($this->items as $orderItem) {
            $order_item_variations = [];
            $order_item_variation_values = [];
            if ($orderItem->order_item_variations) {
                $other_attributes = [];
                foreach ($orderItem->order_item_variations as $order_item_variation) {
                    $size_name = 'default';
                    foreach ($order_item_variation->order_item_variation_values as $order_item_variation_value) {
                        if ($order_item_variation_value->attribute_name == "Size") {
                            $size_name = $order_item_variation_value->attribute_value_name;
                        } else {
                            $other_attributes[$size_name][$order_item_variation_value->attribute_name][] = $order_item_variation_value->attribute_value_name;
                        }
                    }
                }

                foreach ($orderItem->order_item_variations as $order_item_variation) {
                    foreach ($order_item_variation->order_item_variation_values as $order_item_variation_value) {
                        if ($order_item_variation_value->attribute_name == "Size") {
                            if (array_key_exists(
                                $order_item_variation_value->attribute_value_name,
                                $order_item_variation_values
                            )) {
                                $order_item_variation_values[$order_item_variation_value->attribute_value_name]["qty"] = $order_item_variation_values[$order_item_variation_value->attribute_value_name]["qty"] + $order_item_variation_value->qty;
                                $order_item_variation_values[$order_item_variation_value->attribute_value_name]["delivered_qty"] = $order_item_variation_values[$order_item_variation_value->attribute_value_name]["delivered_qty"] + $order_item_variation_value->delivered_qty;
                            } else {
                                $order_item_variation_values[$order_item_variation_value->attribute_value_name] = [
                                    "id" => $order_item_variation_value->id,
                                    "attribute_name" => $order_item_variation_value->attribute_name,
                                    "attribute_value_name" => $order_item_variation_value->attribute_value_name,
                                    "qty" => $order_item_variation_value->qty,
                                    "delivered_qty" => $order_item_variation_value->delivered_qty
                                ];

                                if (isset($other_attributes[$order_item_variation_value->attribute_value_name])) {
                                    $order_item_variation_values[$order_item_variation_value->attribute_value_name]['other_attributes'] = $other_attributes[$order_item_variation_value->attribute_value_name];
                                } else {
                                    $order_item_variation_values[$order_item_variation_value->attribute_value_name]['other_attributes'] = null;
                                }
                            }
                        }
                    }
                }

                if (isset($other_attributes['default'])) {
                    $order_item_variation_values['default'] = $other_attributes;
                }

            }



            $order_item_variations = array_values($order_item_variation_values);

            array_push(
                $orderItems,
                [
                    "id" => $orderItem->id,
                    "quantity" => $orderItem->quantity,
                    "delivered_qty" => $orderItem->delivered_qty,
                    "product_code" => $orderItem->product_code,
                    "product_title" => $orderItem->product_title,
                    "product_price" => $orderItem->product_price,
                    "category_title" => $orderItem->category_title,
                    "order_id" => $orderItem->order_id,
                    "item_type" => $orderItem->item_type,
                    "supplier" => $orderItem->supplier,
                    "supplier_status" => $orderItem->supplier_status,
                    "embellishment_supplier" => $orderItem->embellishment_supplier,
                    "embellishment_status" => $orderItem->embellishment_status,
                    "factory_status" => $orderItem->factory_status,
                    "order_item_variations" => $order_item_variations
                ]
            );
        }


        return [
            "id" => $this->id,
            "merch_order_id" => $this->merch_order_id,
            "comment" => $this->comment,
            "sponsor_code" => $this->sponsor_code,
            "sponsor_amount" => $this->sponsor_amount,
            "shipping_cost" => $this->shipping_cost,
            "cart_total" => $this->cart_total,
            "total" => $this->total,
            "status" => $this->status,
            "is_active" => $this->is_active,
            "pickup_store" => $this->pickup_store,
            "shipping_address" => $this->shipping_address,
            "billing_address" => $this->billing_address,
            "grade" => $this->grade,
            "customer_name" => $this->customer_name,
            "customer_email" => $this->customer_email,
            "event_name" => $this->event_name,
            "club_name" => $this->club_name,
            "status_at_factory" => $this->status_at_factory,
            "enabled_for_warehouse" => $this->enabled_for_warehouse,
            "delivery_no" => $this->delivery_no,
            "job_no" => $this->job_no,
            "order_date" => $this->order_date,
            "delivery_date" => $this->delivery_date,
            "delivery_status" => $this->delivery_status,
            "store_name" => $this->store_name,
            "no_of_boxes_delivered" => $this->no_of_boxes_delivered,
            "order_status" => $this->order_status,
            "po" => $this->po,
            "items" => $orderItems,
            "comments" => $this->comments,
            "delivery_histories" => $this->deliveryHistory,
            "audits" => [
                'CS' => $this->audits()->with('user')->get()->filter(function($item)
                {
                    if($item->tags == 'CS')
                    {
                        return $item;
                    }
                })->toArray(),
                'Factory' => $this->audits()->with('user')->get()->filter(function($item)
                {
                    if($item->tags == 'Factory')
                    {
                        return true;
                    }
                })->toArray(),
                'WH' => $this->audits()->with('user')->get()->filter(function($item)
                {
                    if($item->tags == 'WH')
                    {
                        return true;
                    }
                })->toArray(),
                'Shop' => $this->audits()->with('user')->get()->filter(function($item)
                {
                    if($item->tags == 'Shop')
                    {
                        return true;
                    }
                })->toArray()
            ],
        ];
    }
}
