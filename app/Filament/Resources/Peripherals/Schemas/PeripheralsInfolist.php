<?php

namespace App\Filament\Resources\Peripherals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\ImageEntry;

class PeripheralsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item_type'),

                ImageEntry::make('image')
                ->disk('public'),

                TextEntry::make('asset_code'),

                TextEntry::make('serial_number'),

                TextEntry::make('model'),

                TextEntry::make('date_acquired')
                    ->date(),
                    
                TextEntry::make('description'),
            ]);
    }
}
