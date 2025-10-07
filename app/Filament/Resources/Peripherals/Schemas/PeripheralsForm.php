<?php

namespace App\Filament\Resources\Peripherals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PeripheralsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_type')
                    ->required(),
                TextInput::make('asset_code')
                    ->required(),
                TextInput::make('serial_number')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                DatePicker::make('date_acquired'),
                TextInput::make('description'),
            ]);
    }
}
