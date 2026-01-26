<?php

namespace App\Filament\Resources\DispatchTrips\Pages;

use App\Filament\Resources\DispatchTrips\DispatchTripResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDispatchTrip extends ViewRecord
{
    protected static string $resource = DispatchTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
