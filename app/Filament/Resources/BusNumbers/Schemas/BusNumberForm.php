<?php

namespace App\Filament\Resources\BusNumbers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BusNumberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                TextInput::make('bus_number')
                    ->maxValue(10)
                    ->label('Bus Number')
                    ->required()
                    ->unique(table: 'bus_numbers', column: 'bus_number', ignoreRecord: true)
                    ->validationMessages([
                        'maxValue' => 'Bus Number cannot be greater than 10 characters.',
                        'unique' => 'This bus number already exists.',
                    ]),

                TextInput::make('bus_model')
                    ->label('Bus Model')
                    ->datalist([
                        'YUTONG',
                        'HIGER',
                        'ZHONGTONG',
                    ])
                    ->maxValue(15)
                    ->validationMessages([
                        'maxValue' => 'Bus Model cannot be greater than 15 characters.',
                    ]),

                TextInput::make('bus_class')
                    ->label('Bus Class')
                    ->maxValue(50)
                    ->validationMessages([
                        'maxValue' => 'Bus Class cannot be greater than 50 characters.',
                    ]),

                TextInput::make('bus_type')
                    ->label('Bus Type')
                    ->maxValue(25)
                    ->validationMessages([
                        'maxValue' => 'Bus Type cannot be greater than 25 characters.',
                    ]),

                TextInput::make('seat_capacity')
                    ->label('Seat Capacity')
                    ->maxValue(2)
                    ->validationMessages([
                        'maxValue' => 'Seat Capacity cannot be greater than 2 characters.',
                    ]),

                Select::make('driver_id')
                    ->label('Driver')
                    ->relationship('driver', 'driver_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('conductor_id')
                    ->label('Conductor')
                    ->relationship('conductor', 'conductor_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
