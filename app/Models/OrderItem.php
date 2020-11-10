<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $with = ['order_item_variations'];

    public function order_item_variations()
    {
        return $this->hasMany(OrderItemVariation::class);
    }
}
