<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class BluetoothThermalReceiptController extends Controller
{
    public function printReceipt($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $orderItems = $order->orderItems->map(function ($item) {
            return [
                'product_name' => $item->product->name,
                'quantity' => $item->qty,
                'unit_price' => number_format($item->unit_price, 2),
                'sub_total' => number_format($item->sub_total, 2),
            ];
        });

        $data = [
            'title' => 'தூண்டில் மீன்கள்',
            'moto' => 'Feel the taste of fresh fish',
            'order_id' => $order->id,
            'customer_name' => $order->user->name,
            'order_date' => $order->created_at->format('Y-m-d H:i:s'),
            'items' => $orderItems,
            'total_amount' => number_format($order->total_amount, 2),
        ];

        return response()->json(['data' => $data], 200);
    }
}
