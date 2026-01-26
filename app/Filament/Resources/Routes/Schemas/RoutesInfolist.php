<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RoutesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('from'),
                TextEntry::make('to'),
                TextEntry::make('distance')
                    ->numeric(),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
