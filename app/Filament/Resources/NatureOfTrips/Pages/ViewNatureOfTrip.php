<?php

namespace App\Filament\Resources\NatureOfTrips\Pages;

use App\Filament\Resources\NatureOfTrips\NatureOfTripResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNatureOfTrip extends ViewRecord
{
    protected static string $resource = NatureOfTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
