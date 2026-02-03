<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class RoutesForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from')
                    ->label('From')
                    ->maxValue(50)
                    ->required(),

                TextInput::make('to')
                    ->label('To')
                    ->maxValue(50)
                    ->required(),

                TextInput::make('distance')
                    ->label('Distance (in km)')
                    ->required()
                    ->maxValue(300)
                    ->inputMode('decimal')
                    ->validationMessages([
                        'required' => 'Distance is required',
                        'maxValue' => 'Distance cannot be greater than 300 digits.',
                    ]),

                TextInput::make('remarks')
                    ->maxValue(100)
                    ->validationMessages([
                        'maxValue' => 'Remarks cannot be greater than 100 characters.',
                    ]),
            ]);
    }
}
