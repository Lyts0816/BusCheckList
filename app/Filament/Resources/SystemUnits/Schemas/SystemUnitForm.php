<?php

namespace App\Filament\Resources\SystemUnits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SystemUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('asset_code'),
                TextInput::make('serial_number')
                ->unique(ignoreRecord: true),
                TextInput::make('model'),
                DatePicker::make('date_aquired'),
                TextInput::make('OS')
                    ->label('OS'),
                TextInput::make('windows_serial_number'),
                TextInput::make('microsoft_serial_number'),
                TextInput::make('ram'),
                TextInput::make('storage'),
                TextInput::make('processor'),
                TextInput::make('ip_address')
                    ->label('IP Address'),
                TextInput::make('description'),
            ]);
    }
}
