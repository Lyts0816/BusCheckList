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
                    ->required()
                    ->helperText('LAST NAME, FIRST NAME M.I.')
                    ->maxValue(50)
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'required' => 'Driver name is required',
                        'maxValue' => 'Driver name cannot be greater than 50 characters.',
                        'unique' => 'Driver name already exists.',
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
