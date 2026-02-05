<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDispatchedTrips extends EditRecord
{
    protected static string $resource = DispatchedTripsResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert stored total_travel_time_minutes back to hours + minutes
        if (isset($data['total_travel_time_minutes'])) {
            $data['hours'] = intdiv($data['total_travel_time_minutes'], 60);
            $data['minutes'] = $data['total_travel_time_minutes'] % 60;
        }

        // Convert stored total_add_time_minutes back to hours + minutes
        if (isset($data['total_add_time_minutes'])) {
            $data['add_time_hours'] = intdiv($data['total_add_time_minutes'], 60);
            $data['add_time_minutes'] = $data['total_add_time_minutes'] % 60;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            // DeleteAction::make(),
        ];
    }

}