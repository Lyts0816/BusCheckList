<?php

namespace App\Filament\Resources\DispatchTrips\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DispatchTripInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('trip_number'),
                TextEntry::make('from'),
                TextEntry::make('to'),
                TextEntry::make('bus_number'),
                TextEntry::make('bus_class'),
                TextEntry::make('nature_of_trip'),
                TextEntry::make('date_time_in_terminal')
                    ->dateTime(),
                TextEntry::make('date_time_of_parking')
                    ->dateTime(),
                TextEntry::make('date_time_of_departure')
                    ->dateTime(),
                TextEntry::make('date_time_of_arrival')
                    ->dateTime(),
                TextEntry::make('idle_time_start')
                    ->time(),
                TextEntry::make('idle_time_end')
                    ->time(),
                TextEntry::make('driver'),
                TextEntry::make('conductor'),
                TextEntry::make('total_travel_time_minutes')
                    ->numeric(),
                TextEntry::make('total_add_time_minutes')
                    ->numeric(),
                TextEntry::make('km_run')
                    ->numeric(),
                TextEntry::make('ticket_number')
                    ->numeric(),
                TextEntry::make('passengers_on_board')
                    ->numeric(),
                TextEntry::make('baggage_amount')
                    ->numeric(),
                TextEntry::make('baggage_ticket_no')
                    ->numeric(),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
