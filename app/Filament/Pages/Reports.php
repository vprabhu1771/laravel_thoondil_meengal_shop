<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderItem;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';
    
    public $totalSales;

    public $productOrders;

    public function mount()
    {
        $this->totalSales = Order::sum('total_amount');

        $this->productOrders = OrderItem::with('product')
            ->select('product_id')
            ->selectRaw('SUM(qty) as total_quantity, SUM(qty * unit_price) as total_amount')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('product_id')
            ->get();
    }
}
