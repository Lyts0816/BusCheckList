<?php

namespace App\Filament\Resources\BusClasses\Pages;

use App\Filament\Resources\BusClasses\BusClassResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBusClass extends ViewRecord
{
    protected static string $resource = BusClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
