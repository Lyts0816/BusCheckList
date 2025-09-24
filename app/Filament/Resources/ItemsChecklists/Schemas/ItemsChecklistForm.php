<?php

namespace App\Filament\Resources\ItemsChecklists\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemsChecklistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_type')
                    ->required(),
                TextInput::make('item_name')
                    ->required(),
                Select::make('bus_id')
                    ->label('Bus Number')
                    ->relationship('bus', 'bus_number')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('item_asset_code')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                DatePicker::make('date_checked')
                    ->required(),
                TextInput::make('remarks'),
            ]);
    }
}
