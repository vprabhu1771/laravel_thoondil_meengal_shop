<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use App\Enums\Timing;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getReport(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'selectedTiming' => 'nullable|string|in:' . implode(',', Timing::getValues()),
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);

        // Retrieve input values
        $selectedTiming = $validated['selectedTiming'] ?? null;
        $startDate = Carbon::parse($validated['startDate'])->startOfDay();
        $endDate = Carbon::parse($validated['endDate'])->endOfDay();

        // Fetch total sales
        $totalSales = Order::query()
            ->when($selectedTiming, function ($query) use ($selectedTiming) {
                $query->where('timings', $selectedTiming);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Fetch product orders and their quantities
        $productOrders = OrderItem::with('product')
            ->select('product_id')
            ->selectRaw('SUM(qty) as total_quantity, SUM(qty * unit_price) as total_amount')
            ->whereHas('order', function ($orderQuery) use ($startDate, $endDate, $selectedTiming) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                    ->when($selectedTiming, function ($query) use ($selectedTiming) {
                        $query->where('timings', $selectedTiming);
                    });
            })
            ->groupBy('product_id')
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name, // Assuming `name` is the field in the `products` table
                    'total_quantity' => $item->total_quantity,
                    'total_amount' => $item->total_amount,
                ];
            });

        // Return the data in a JSON response
        return response()->json([
            'totalSales' => $totalSales,
            'productOrders' => $productOrders,
        ]);
    }
}