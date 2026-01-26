<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DriversForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('driver_name')
                    ->required(),
                TextInput::make('status'),
                TextInput::make('remarks'),
            ]);
    }
}
