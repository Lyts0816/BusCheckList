<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DriversInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('driver_name'),
                TextEntry::make('status'),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
