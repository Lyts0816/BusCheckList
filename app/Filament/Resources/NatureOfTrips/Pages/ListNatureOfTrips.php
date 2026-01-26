<?php

namespace App\Filament\Resources\NatureOfTrips\Pages;

use App\Filament\Resources\NatureOfTrips\NatureOfTripResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNatureOfTrips extends ListRecords
{
    protected static string $resource = NatureOfTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
