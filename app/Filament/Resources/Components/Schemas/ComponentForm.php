<?php

namespace App\Filament\Resources\Components\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('asset_type')
                    ->options(['system_unit' => 'System unit', 'printer' => 'Printer', 'peripheral' => 'Peripheral'])
                    ->required(),

                TextInput::make('name')
                    ->required(),
                    
                TextInput::make('description'),
            ]);
    }
}
