<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Resources\Pages\CreateRecord;


class CreateDispatchedTrips extends CreateRecord
{
    protected static string $resource = DispatchedTripsResource::class;

    protected ?string $heading = 'New Trip';

    protected ?string $subheading = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert hours + minutes to total_travel_time_minutes
        $data['total_travel_time_minutes'] =
            (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);

        // Convert add_time_hours + add_time_minutes to total_add_time_minutes
        $data['total_add_time_minutes'] =
            (($data['add_time_hours'] ?? 0) * 60) + ($data['add_time_minutes'] ?? 0);

        // Remove temporary fields
        unset($data['hours'], $data['minutes'], $data['add_time_hours'], $data['add_time_minutes']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
