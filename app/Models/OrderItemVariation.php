<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemVariation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['order_item_variation_values'];

    public function order_item_variation_values()
    {
        return $this->hasMany(OrderItemVariationValue::class);
    }
}
