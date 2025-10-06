<?php

namespace App\Filament\Resources\Peripherals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PeripheralsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user'),
                TextEntry::make('department'),
                TextEntry::make('item_type'),
                TextEntry::make('model'),
                TextEntry::make('serial_number'),
                TextEntry::make('asset_code'),
                TextEntry::make('date_acquired')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('description'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
