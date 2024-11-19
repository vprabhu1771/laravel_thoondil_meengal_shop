<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

// For redirecting to index page
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
