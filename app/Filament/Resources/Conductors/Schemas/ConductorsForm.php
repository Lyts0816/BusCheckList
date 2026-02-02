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
                    ->required()
                    ->helperText('LAST NAME, FIRST NAME M.I.')
                    ->maxValue(50)
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'required' => 'Conductor name is required',
                        'maxValue' => 'Conductors name cannot be greater than 50 characters.',
                        'unique' => 'Conductor name already exists.',
                    ]),

                TextInput::make('status')
                    ->maxValue(20)
                    ->validationMessages([
                        'maxValue' => 'Status cannot be greater than 20 characters.',
                    ]),

                TextInput::make('remarks')
                    ->maxValue(100)
                    ->validationMessages([
                        'maxValue' => 'Remarks cannot be greater than 100 characters.',
                    ]),
            ]);
    }
}
