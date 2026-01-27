<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDispatchedTrips extends ListRecords
{
    protected static string $resource = DispatchedTripsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
