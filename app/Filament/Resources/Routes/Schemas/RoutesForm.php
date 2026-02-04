<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rule;

class RoutesForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from')
                    ->label('From')
                    ->maxValue(50)
                    ->required()
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $recordId = request()->route('record');
                                
                                $query = \App\Models\Routes::where('from', $value)
                                    ->where('to', $get('to'));
                                
                                if ($recordId) {
                                    $query->where('id', '!=', $recordId);
                                }
                                    
                                if ($query->exists()) {
                                    $fail('This route (From → To combination) already exists.');
                                }
                            };
                        },
                    ]),

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
