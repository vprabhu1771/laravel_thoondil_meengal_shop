<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
}
