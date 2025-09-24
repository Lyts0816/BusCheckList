<?php

namespace App\Filament\Resources\Buses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BusInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('bus_number'),
                TextEntry::make('model'),
                TextEntry::make('status'),
                TextEntry::make('base_location'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
