<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'qty',
    ];

    public function customer() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product() 
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function totalPrice()
    {
        return $this->qty * $this->product->price;
    }

    public static function grandTotal($customId)
    {
        $cartItems = Cart::where('user_id', $customId)->get();
        $total = $cartItems->sum(function ($item) {
            return $item->totalPrice();
        });

        return $total;
    }
}