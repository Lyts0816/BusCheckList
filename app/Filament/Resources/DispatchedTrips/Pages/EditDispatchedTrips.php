<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDispatchedTrips extends EditRecord
{
    protected static string $resource = DispatchedTripsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

        protected function mutateFormDataBeforeFill(array $data): array
        {
            // Convert total_travel_time_minutes to hours and minutes
            $travelTotalMinutes = $data['total_travel_time_minutes'] ?? 0;
            $data['hours'] = intdiv($travelTotalMinutes, 60);
            $data['minutes'] = $travelTotalMinutes % 60;
            
            // Convert total_add_time_minutes to add_time_hours and add_time_minutes
            $addTimeTotalMinutes = $data['total_add_time_minutes'] ?? 0;
            $data['add_time_hours'] = intdiv($addTimeTotalMinutes, 60);
            $data['add_time_minutes'] = $addTimeTotalMinutes % 60;
            
            return $data;
        }

        protected function mutateFormDataBeforeSave(array $data): array
        {
            // Convert hours + minutes back to total_travel_time_minutes
            $data['total_travel_time_minutes'] = 
                (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);
            
            // Convert add_time_hours + add_time_minutes back to total_add_time_minutes
            $data['total_add_time_minutes'] = 
                (($data['add_time_hours'] ?? 0) * 60) + ($data['add_time_minutes'] ?? 0);
            
            // Remove temporary form fields
            unset($data['hours'], $data['minutes'], $data['add_time_hours'], $data['add_time_minutes']);
            
            return $data;
        }

}