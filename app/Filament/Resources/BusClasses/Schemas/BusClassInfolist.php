<?php

namespace App\Filament\Resources\BusClasses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BusClassInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('class_name'),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
