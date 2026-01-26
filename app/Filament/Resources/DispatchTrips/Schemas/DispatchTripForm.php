<?php

namespace App\Filament\Resources\DispatchTrips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class DispatchTripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trip_number')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('from')
                    ->required(),
                TextInput::make('to')
                    ->required(),
                TextInput::make('bus_number')
                    ->required(),
                TextInput::make('bus_class')
                    ->required(),
                TextInput::make('nature_of_trip')
                    ->required(),
                DateTimePicker::make('date_time_in_terminal')
                    ->required(),
                DateTimePicker::make('date_time_of_parking')
                    ->required(),
                DateTimePicker::make('date_time_of_departure')
                    ->required(),
                DateTimePicker::make('date_time_of_arrival')
                    ->required(),
                TimePicker::make('idle_time_start')
                    ->required(),
                TimePicker::make('idle_time_end')
                    ->required(),
                TextInput::make('driver')
                    ->required(),
                TextInput::make('conductor')
                    ->required(),
                TextInput::make('total_travel_time_minutes')
                    ->numeric()
                    ->default(0),
                TextInput::make('total_add_time_minutes')
                    ->numeric()
                    ->default(0),
                TextInput::make('km_run')
                    ->numeric()
                    ->default(0),
                // TextInput::make('ticket_number')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('passengers_on_board')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('baggage_amount')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('baggage_ticket_no')
                //     ->numeric()
                //     ->default(0),
                TextInput::make('remarks'),
            ])->columns(2);
    }
}
