<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoutesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from')
                    ->required(),
                TextInput::make('to')
                    ->required(),
                TextInput::make('distance')
                    ->required()
                    ->numeric(),
                TextInput::make('remarks'),
            ]);
    }
}
