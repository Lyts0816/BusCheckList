<?php

namespace App\Filament\Resources\NatureOfTrips\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NatureOfTripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nature_of_trip_name')
                    ->required()
                    ->maxValue(30)
                    ->validationMessages([
                        'required' => 'Nature of Trip Name is required',
                        'maxValue' => 'Nature of Trip Name must not exceed 30 characters',
                    ]),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->maxValue(500),
                    
                TextInput::make('remarks')
                    ->maxValue(100)
                    ->validationMessages([
                        'maxValue' => 'Remarks cannot be greater than 100 characters.',
                    ]),
            ]);
    }
}
