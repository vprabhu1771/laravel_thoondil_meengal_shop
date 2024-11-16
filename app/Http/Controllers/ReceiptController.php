<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;

class ReceiptController extends Controller
{
    public function printReceipt($id)
    {

        $order = Order::find($id);        

        $data = [
            'title' => 'Thoondil Meengal',
            'order' => $order
        ];

        return view('receipt', $data);
    }

}
