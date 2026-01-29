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
                    // Convert hours + minutes to total minutes
                    $data['total_travel_time_minutes'] = 
                        (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);
                    
                    // Remove temporary fields
                    unset($data['hours'], $data['minutes']);
                    
                    return static::getModel()::create($data);
                }),
        ];
    }
}
