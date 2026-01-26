<?php

namespace App\Filament\Resources\Conductors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConductorsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('conductor_name')
                    ->required(),
                TextInput::make('status'),
                TextInput::make('remarks'),
            ]);
    }
}
