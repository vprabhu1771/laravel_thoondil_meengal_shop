<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderItem;

use App\Models\User;

class Reports extends Page
{
// Giving an icon NAv bar

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Evening Hotel';

// Giving name in side NAv bar

    protected static ?string $navigationLabel = 'Settlement Report';

// To arrane in order - NAv bar

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.reports';
    
    public $totalSales;

    public $productOrders;

    public $selectedUser;
    public $users;

    public function mount()
    {
        $this->users = User::all(); // Fetch all users for the dropdown
        $this->selectedUser = null; // Default to no user selected
        $this->fetchData();
    }

    public function updatedSelectedUser($value)
    {
        $this->fetchData(); // Fetch data when user selection changes
    }
    
    private function fetchData()
    {
        $query = Order::query();

        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        $this->totalSales = $query->sum('total_amount');

        $this->productOrders = OrderItem::with('product')
            ->select('product_id')
            ->selectRaw('SUM(qty) as total_quantity, SUM(qty * unit_price) as total_amount')
            ->when($this->selectedUser, function ($query) {
                $query->whereHas('order', function ($orderQuery) {
                    $orderQuery->where('user_id', $this->selectedUser);
                });
            })
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('product_id')
            ->get();
    }
}
