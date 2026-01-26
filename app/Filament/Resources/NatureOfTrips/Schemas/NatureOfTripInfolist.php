<?php

namespace App\Filament\Resources\NatureOfTrips\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NatureOfTripInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nature_of_trip_name'),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
