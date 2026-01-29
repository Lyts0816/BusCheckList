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
            CreateAction::make()
                ->using(function (array $data): \Illuminate\Database\Eloquent\Model {
                    // Convert hours + minutes to total_travel_time_minutes
                    $data['total_travel_time_minutes'] = 
                        (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);
                    
                    // Convert add_time_hours + add_time_minutes to total_add_time_minutes
                    $data['total_add_time_minutes'] = 
                        (($data['add_time_hours'] ?? 0) * 60) + ($data['add_time_minutes'] ?? 0);
                    
                    // Remove temporary fields
                    unset($data['hours'], $data['minutes'], $data['add_time_hours'], $data['add_time_minutes']);
                    
                    return static::getModel()::create($data);
                }),
        ];
    }
}
