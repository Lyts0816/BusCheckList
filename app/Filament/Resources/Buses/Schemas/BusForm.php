<?php

namespace App\Filament\Resources\Buses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class BusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bus_number')
                    ->required(),
                TextInput::make('model'),
                Select::make('status')
                    ->options([
                        'Operational' => 'Operational',
                        'Non Operational' => 'Non Operational',
                        'Under Maintenance' => 'Under Maintenance',
                    ])
                    ->required(),
                TextInput::make('base_location'),
            ]);
    }
}
