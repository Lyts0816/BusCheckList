<?php

namespace App\Filament\Resources\BusClasses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BusClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('class_name')
                    ->label('Bus Class Name')
                    ->required()
                    ->helperText('Bus Class Name should be on Capital letters.')
                    ->maxValue(50)
                    ->validationMessages([
                        'maxValue' => 'Bus Model cannot be greater than 50 characters.',
                    ]),

                TextInput::make('description')
                    ->columnSpanFull()
                    ->maxValue(100)
                    ->validationMessages([
                        'maxValue' => 'Bus Model cannot be greater than 100 characters.',
                    ]),

                TextInput::make('remarks')
                    ->maxValue(100)
                    ->columnSpanFull()
                    ->validationMessages([
                        'maxValue' => 'Remarks cannot be greater than 100 characters.',
                    ]),
            ]);
    }
}
