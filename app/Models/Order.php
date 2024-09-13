<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price'   
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotalPrice()
    {
        $this->total_price = $this->orderItems->sum('total_price');
        $this->save();
    }
}
