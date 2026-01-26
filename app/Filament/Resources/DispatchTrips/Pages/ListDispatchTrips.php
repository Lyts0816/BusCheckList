<?php

namespace App\Filament\Resources\DispatchTrips\Pages;

use App\Filament\Resources\DispatchTrips\DispatchTripResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDispatchTrips extends ListRecords
{
    protected static string $resource = DispatchTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
