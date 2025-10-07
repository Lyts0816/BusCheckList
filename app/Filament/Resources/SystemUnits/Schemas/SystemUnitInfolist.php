<?php

namespace App\Filament\Resources\SystemUnits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SystemUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('asset_code'),
                TextEntry::make('serial_number'),
                TextEntry::make('model'),
                TextEntry::make('date_aquired')
                    ->date(),
                TextEntry::make('OS')
                    ->date(),
                TextEntry::make('windows_serial_number')
                    ->date(),
                TextEntry::make('microsoft_serial_number'),
                TextEntry::make('ram')
                    ->date(),
                TextEntry::make('storage')
                    ->date(),
                TextEntry::make('processor'),
                TextEntry::make('ip_address'),
                TextEntry::make('description'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
