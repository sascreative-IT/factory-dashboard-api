<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements Auditable
{
    use HasFactory;
    protected $guarded = [];
    use \OwenIt\Auditing\Auditable;

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function comments()
    {
        return $this->hasMany(OrderComments::class);
    }

    public function generateTags(): array
    {
        if (isset(Auth::user()->department)) {
            return [
                Auth::user()->department
            ];
        }
    }
}
