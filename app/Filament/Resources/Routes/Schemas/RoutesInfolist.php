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
                TextEntry::make('from')
                    ->label('From'),

                TextEntry::make('to')
                    ->label('To'),

                TextEntry::make('distance')
                    ->label('Distance (in km)')
                    ->numeric(),

                TextEntry::make('remarks'),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                    
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}
