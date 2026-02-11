<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDispatchedTrips extends ViewRecord
{
    protected static string $resource = DispatchedTripsResource::class;

    protected ?string $heading = 'View Trip';

    protected ?string $subheading = null;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
