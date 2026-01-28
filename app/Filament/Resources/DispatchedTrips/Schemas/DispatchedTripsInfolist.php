<?php

namespace App\Filament\Resources\DispatchedTrips\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DispatchedTripsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([

                Section::make('Trip Information')
                    ->schema([
                        TextEntry::make('trip_number')
                            ->label('Trip Number'),

                        TextEntry::make('route.from')
                            ->label('From'),

                        TextEntry::make('route.to')
                            ->label('To'),

                        TextEntry::make('km_run')
                            ->label('KM Run'),

                        TextEntry::make('natureOfTrip.nature_of_trip_name')
                            ->label('Nature of Trip'),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

                Section::make('Bus Details')
                    ->schema([
                        TextEntry::make('busNumber.bus_number')
                            ->label('Bus Number'),

                        TextEntry::make('busClass.class_name')
                            ->label('Bus Class'),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

                Section::make('Personnel')
                    ->schema([
                        TextEntry::make('driver.driver_name')
                            ->label('Driver'),

                        TextEntry::make('conductor.conductor_name')
                            ->label('Conductor'),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

                Section::make('DateTime Information')
                    ->schema([
                        TextEntry::make('date_time_in_terminal')
                            ->label('Date/Time in Terminal')
                            ->dateTime(),

                        TextEntry::make('date_time_of_parking')
                            ->label('Date/Time of Parking')
                            ->dateTime(),

                        TextEntry::make('date_time_of_arrival')
                            ->label('Date/Time of Arrival')
                            ->dateTime(),

                        TextEntry::make('date_time_of_departure')
                            ->label('Date/Time of Departure')
                            ->dateTime(),

                        TextEntry::make('idle_time_start')
                            ->label('Idle Time Start')
                            ->time(),

                        TextEntry::make('idle_time_end')
                            ->label('Idle Time End')
                            ->time(),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

                Section::make('Trip Statistics')
                    ->schema([
                        TextEntry::make('total_travel_time_minutes')
                            ->label('Total Travel Time (Hours)'),

                        TextEntry::make('total_add_time_minutes')
                            ->label('Total Add Time (Minutes)'),

                        TextEntry::make('remarks')
                            ->label('Remarks')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

            ])->columns(4);
    }
}
