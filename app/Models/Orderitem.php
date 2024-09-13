<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
        'total_price'

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function boot()
    {
        parent::boot();

        // Automatically calculate total price based on price and quantity
        static::creating(function ($orderItem) {
            $orderItem->total_price = $orderItem->price * $orderItem->qty;
        }
    );

    }
}