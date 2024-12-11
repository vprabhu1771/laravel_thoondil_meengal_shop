<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\Timing;

class Reports extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Evening Hotel';
    protected static ?string $navigationLabel = 'Settlement Report';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.reports';

    public $totalSales;
    public $productOrders;
    public $selectedTiming;
    public $timings;

    public function mount(): void
    {
        $this->timings = Timing::getValues();
        $this->selectedTiming = null;
        $this->form->fill(['selectedTiming' => $this->selectedTiming]);
        $this->fetchData();
    }

    public function updatedSelectedTiming($value): void
    {
        $this->selectedTiming = $value;
        $this->fetchData();
    }

    private function fetchData(): void
    {
        $query = Order::query();

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

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('selectedTiming')
                ->label('Select Timing')
                ->options(array_combine($this->timings, $this->timings))
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->updatedSelectedTiming($state)),
        ];
    }
}

