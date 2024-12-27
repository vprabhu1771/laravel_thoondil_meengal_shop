<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();
            $customer_id = $user->id;

            // Query the database to get the count of orders grouped by timings
            $timingCounts = Order::select('timings', DB::raw('COUNT(*) as count'))
                ->where('user_id', $customer_id)
                ->groupBy('timings')
                ->get()
                ->keyBy('timings');

            // Format the timing counts with defaults for missing values
            $timingData = [
                'All'       => $timingCounts->sum('count'), // Total of all timings
                'Morning'   => $timingCounts['Morning']->count ?? 0,
                'Afternoon' => $timingCounts['Afternoon']->count ?? 0,
                'Evening'   => $timingCounts['Evening']->count ?? 0,
            ];

            // Fetch all orders for the user
            $orders = Order::where('user_id', $customer_id)
                ->orderBy('id', 'asc')
                ->get();

            $transformedOrders = $orders->map(function($row){
        
                return[
                    'id' => $row->id,
                    'customer_name' => $row->user->name,
                    'timings' => $row->timings,
                    'total_amount' => $row->total_amount,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at
                ];
            });

            // Return a response with timing counts and the orders
            return response()->json([
                'timing_counts' => $timingData,
                'data'        => $transformedOrders,
            ], 200);

        } catch (\Exception $e) {
            // Log the detailed error message for debugging
            Log::error('Error fetching orders: ' . $e->getMessage(), [
                'exception' => $e,
                'trace'     => $e->getTraceAsString(),
            ]);

            // Return a response with the main error cause
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $customer_id = $user->id;

        // Validate the request
        $validatedData = $request->validate([
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.qty' => 'required|integer|min:1',
            'order_items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $customer_id,
                'total_amount' => 0, // Will be calculated
                'order_status' => 'pending',
                'payment_method' => $validatedData['payment_method'],
            ]);

            $totalAmount = 0;

            foreach ($validatedData['order_items'] as $item) {
                $amount = $item['qty'] * $item['unit_price'];
                $totalAmount += $amount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $amount,
                    'discount' => 0
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);

            // Clear the cart
            $user->cartItems()->delete();

            DB::commit();

            return response()->json(['data' => $order->load('orderItems')], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order creation failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json(['data' => $order->load('orderItems')], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}