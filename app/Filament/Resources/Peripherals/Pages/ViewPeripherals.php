<?php

namespace App\Filament\Resources\Peripherals\Pages;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPeripherals extends ViewRecord
{
    protected static string $resource = PeripheralsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
