
<x-filament::page>
    <div class="p-6">
        <h2 class="text-2xl font-bold">Sales Report</h2>
        <p class="mt-4 text-lg">Total Sales: <strong>{{ number_format($totalSales, 2) }}</strong></p>
    </div>

    <div class="p-6">
        <h2 class="text-2xl font-bold">Product Orders Report - Current Month {{ \Carbon\Carbon::now()->format('F Y') }}</h2>
        
        <div class="mt-6">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">Product Name</th>
                        <th class="border border-gray-300 px-4 py-2">Total Quantity Ordered</th>
                        <th class="border border-gray-300 px-4 py-2">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productOrders as $order)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $order->product->name ?? 'Unknown Product' }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $order->total_quantity }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ number_format($order->total_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::page>
