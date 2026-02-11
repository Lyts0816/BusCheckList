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

                        TextEntry::make('dispatchSheet.route.from')
                            ->label('From'),

                        TextEntry::make('dispatchSheet.route.to')
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

                        TextEntry::make('busNumber.bus_class')
                            ->label('Bus Class'),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

                Section::make('Time Information')
                    ->schema([
                        TextEntry::make('time_in_terminal')
                            ->label('Time in Terminal')
                            ->time(),

                        TextEntry::make('time_of_parking')
                            ->label('Time of Parking')
                            ->time(),

                        TextEntry::make('time_of_arrival')
                            ->label('Time of Arrival')
                            ->time(),

                        TextEntry::make('time_of_departure')
                            ->label('Time of Departure')
                            ->time(),

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
                            ->label('Total Travel Time')
                            ->formatStateUsing(
                                fn($state) =>
                                $state ? intdiv($state, 60) . ' hour' . (intdiv($state, 60) !== 1 ? 's' : '') .
                                    ' and ' . ($state % 60) . ' minute' . (($state % 60) !== 1 ? 's' : '') : '0 minutes'
                            ),

                        TextEntry::make('total_add_time_minutes')
                            ->label('Total Add Time')
                            ->formatStateUsing(
                                fn($state) =>
                                $state ? intdiv($state, 60) . ' hour' . (intdiv($state, 60) !== 1 ? 's' : '') .
                                    ' and ' . ($state % 60) . ' minute' . (($state % 60) !== 1 ? 's' : '') : '0 minutes'
                            ),
                    ])
                    ->columns(2)
                    ->columnSpan(4)
                    ->compact(),

                TextEntry::make('remarks')
                    ->label('Remarks')
                    ->columnSpanFull(),

            ])->columns(4);
    }
}
