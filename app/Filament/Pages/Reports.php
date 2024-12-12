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

    // Giving an icon NAv bar
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Hotel Shop';

    // Giving name in side NAv bar
    protected static ?string $navigationLabel = 'Settlement Report';

    // To arrane in order - NAv bar
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.reports';

    public $totalSales;
    public $productOrders;
    public $selectedTiming;
    public $timings;
    public $startDate;
    public $endDate;

    public function mount(): void
    {
        $this->timings = Timing::getValues();
        $this->selectedTiming = null;
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->form->fill([
            'selectedTiming' => $this->selectedTiming,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
        $this->fetchData();
    }

    public function updatedSelectedTiming($value): void
    {
        $this->selectedTiming = $value;
        $this->fetchData();
    }

    public function updatedStartDate($value): void
    {
        $this->startDate = $value;
        $this->fetchData();
    }

    public function updatedEndDate($value): void
    {
        $this->endDate = $value;
        $this->fetchData();
    }

    private function fetchData(): void
    {
        $query = Order::query();

        if ($this->selectedTiming) {
            $query->where('timings', $this->selectedTiming);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $this->totalSales = $query->sum('total_amount');

        $this->productOrders = OrderItem::with('product')
            ->select('product_id')
            ->selectRaw('SUM(qty) as total_quantity, SUM(qty * unit_price) as total_amount')
            ->whereHas('order', function ($orderQuery) {
                $orderQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
            Forms\Components\DatePicker::make('startDate')
                ->label('Start Date')
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->updatedStartDate($state)),
            Forms\Components\DatePicker::make('endDate')
                ->label('End Date')
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->updatedEndDate($state)),
        ];
    }
}

