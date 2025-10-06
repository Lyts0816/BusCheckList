<?php

namespace App\Filament\Resources\Peripherals\Pages;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeripherals extends ListRecords
{
    protected static string $resource = PeripheralsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
