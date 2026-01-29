<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDispatchedTrips extends CreateRecord
{
    protected static string $resource = DispatchedTripsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert hours + minutes to total minutes
        $data['total_travel_time_minutes'] = 
            (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);
        
        // Remove temporary fields
        unset($data['hours'], $data['minutes']);
        
        return $data;
    }
}
