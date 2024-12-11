<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Enums\Timing;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Evening Hotel';
    protected static ?string $navigationLabel = 'Settlement Report';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.reports';

    public $totalSales;
    public $productOrders;
    public $selectedTiming;
    public $timings;

    public function mount()
    {
        $this->timings = Timing::getValues(); // Get all available timings
        $this->selectedTiming = null; // Default to no timing selected
        $this->fetchData();
        logger()->info('Selected Timing:', [$this->selectedTiming]);
    }

    public function updatedSelectedTiming($value)
    {        
        logger()->info('Updated Selected Timing:', [$this->selectedTiming]);
        $this->fetchData(); // Fetch data when timing selection changes
    }

    private function fetchData()
    {
        $query = Order::query();

        // Apply timing filter
        if ($this->selectedTiming) {
            $query->where('timings', $this->selectedTiming);
        }

        $this->totalSales = $query->sum('total_amount');

        $this->productOrders = OrderItem::with('product')
            ->select('product_id')
            ->selectRaw('SUM(qty) as total_quantity, SUM(qty * unit_price) as total_amount')
            ->whereHas('order', function ($orderQuery) {
                $orderQuery->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            })
            ->when($this->selectedTiming, function ($query) {
                $query->whereHas('order', function ($orderQuery) {
                    $orderQuery->where('timings', $this->selectedTiming);
                });
            })
            ->groupBy('product_id')
            ->get();
    }
}
