<x-filament::page>
    <div class="space-y-6">
        <!-- Timing Filter Dropdown, Start Date, and End Date in Same Row -->
        <div>
            {{ $this->form }}
        </div>

        <!-- Total Sales Display -->
        <div class="p-4 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold">Total Sales</h2>
            <p class="text-2xl font-bold text-green-600">ரூ {{ number_format($totalSales, 2) }}</p>
        </div>
        

        <!-- Product Orders Table -->
        <div class="p-4 bg-white shadow rounded-lg">
            <h2 class="text-2xl font-bold">Product Orders Report - Current Month {{ \Carbon\Carbon::now()->format('F Y') }} - {{ $selectedTiming }}</h2>
            
            <div class="mt-6">
                @if($productOrders->isEmpty())
                    <p class="text-gray-600">No orders available for the selected timing.</p>
                @else
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
                                        ரூ {{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif                
            </div>
        </div>
        
    </div>
</x-filament::page>
