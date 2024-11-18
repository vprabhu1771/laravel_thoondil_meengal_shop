<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .receipt-container {
            text-align: center;
            margin: 0 auto;
            width: 72mm;
            height: auto; /* Dynamic height based on content */
        }

        .receipt-header {
            font-size: 30px;
            font-weight: bold;
        }

        .receipt-moto {
            font-size: 20px;
            font-weight: bold;
        }

        .receipt-body {
            margin-top: 20px;
            font-size: 30px;
            text-align: left;
            padding: 0 10px;
        }

        .receipt-footer {
            margin-top: 30px;
            font-size: 30px;
            color: #888;
            text-align: center;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .receipt-table th, .receipt-table td {
            font-size: 20px;
            padding: 5px;
            text-align: left;
        }

        .receipt-table th {
            font-weight: bold;
        }

        .receipt-table td {
            border-top: 1px dashed #000;
        }
    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <div class="receipt-header">
            {{ $title }}            
        </div>
        
        @if($moto)
        <div class="receipt-moto">
            {{ $moto }}
        </div>
        @endif
        
        {{ $order->created_at->format('d-m-Y H:i:s') }}

        <div class="receipt-body">
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $row)
                    <tr>
                        <td>{{ $row->product->name }}</td>
                        <td>{{ $row->qty }}</td>
                        <td>{{ number_format($row->unit_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="receipt-footer">
            Thank you for your purchase!
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function() {
            // Calculate height based on number of rows
            const rowHeight = 40; // Approximate height of each row in pixels
            const baseHeight = 200; // Base height for header and footer in pixels
            const itemCount = {{ count($order->orderItems) }}; // Number of items from server-side
            const totalHeight = baseHeight + (rowHeight * itemCount);

            // Set the calculated height dynamically
            document.getElementById('receipt').style.height = totalHeight + 'px';

            // Trigger print dialog
            window.print();
        };
    </script>
</body>
</html>
