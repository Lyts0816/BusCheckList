<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class RoutesForm
{

    public static function configure(Schema $schema): Schema
    {
        $mindanaoLocalities = [
            // Cities
            'Butuan' => 'Butuan',
            'Cagayan de Oro' => 'Cagayan de Oro',
            'Davao' => 'Davao',
            'General Santos' => 'General Santos',
            'Iligan' => 'Iligan',
            'Koronadal' => 'Koronadal',
            'Pagadian' => 'Pagadian',
            'Zamboanga' => 'Zamboanga',

            // South Cotabato municipalities
            'Polomolok' => 'Polomolok',
            'Tupi' => 'Tupi',
            'Tantangan' => 'Tantangan',
            'Sto. Niño' => 'Sto. Niño',
            'Isulan' => 'Isulan',

            // Sarangani
            'Alabel' => 'Alabel',
            'Glan' => 'Glan',

            // Bukidnon
            'Valencia' => 'Valencia',
            'Malaybalay' => 'Malaybalay',
            'Quezon' => 'Quezon',
        ];

        return $schema
            ->components([
                Select::make('from')
                    ->label('From')
                    ->required()
                    ->options($mindanaoLocalities)
                    ->searchable(),

                Select::make('to')
                    ->label('To')
                    ->required()
                    ->options($mindanaoLocalities)
                    ->searchable(),

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
