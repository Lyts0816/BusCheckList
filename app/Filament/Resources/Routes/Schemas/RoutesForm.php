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
            'Butuan City' => 'Butuan City',
            'Cagayan de Oro City' => 'Cagayan de Oro City',
            'Davao City' => 'Davao City',
            'General Santos City' => 'General Santos City',
            'Iligan City' => 'Iligan City',
            'Koronadal City' => 'Koronadal City',
            'Pagadian City' => 'Pagadian City',
            'Zamboanga City' => 'Zamboanga City',

            // South Cotabato municipalities
            'Polomolok, South Cotabato' => 'Polomolok, South Cotabato',
            'Tupi, South Cotabato' => 'Tupi, South Cotabato',
            'Tantangan, South Cotabato' => 'Tantangan, South Cotabato',
            'Sto. Niño, South Cotabato' => 'Sto. Niño, South Cotabato',
            'T’boli, South Cotabato' => 'T’boli, South Cotabato',
            'Isulan, Sultan Kudarat' => 'Isulan, Sultan Kudarat',

            // Sarangani
            'Alabel, Sarangani' => 'Alabel, Sarangani',
            'Glan, Sarangani' => 'Glan, Sarangani',

            // Bukidnon
            'Valencia City, Bukidnon' => 'Valencia City, Bukidnon',
            'Malaybalay City, Bukidnon' => 'Malaybalay City, Bukidnon',
            'Quezon, Bukidnon' => 'Quezon, Bukidnon',
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
