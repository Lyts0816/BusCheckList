<?php

namespace App\Filament\Resources\DispatchSheets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DispatchSheetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('dispatch_date')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
