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
                TextInput::make('user')
                    ->required(),
                TextInput::make('department'),
                TextInput::make('item_type')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('serial_number')
                    ->required(),
                TextInput::make('asset_code')
                    ->required(),
                DatePicker::make('date_acquired'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                TextInput::make('description'),
            ]);
    }
}
