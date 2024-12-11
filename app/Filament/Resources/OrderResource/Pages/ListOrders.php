<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Order;

use App\Enums\Timing;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make("Spreed Sheet")
                ->url("https://docs.google.com/spreadsheets/d/1eFa23-jN54XADxUpkBbh0E6kQwHZhr4c83oc9HF_Rh4/edit?gid=233517632#gid=233517632")
                ->openUrlInNewTab(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Morning' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('timings', Timing::Morning);
            }),
            'Afternoon' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('timings', Timing::Afternoon);
            }),
            'Evening' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('timings', Timing::Evening);
            }),
        ];
    }
}
