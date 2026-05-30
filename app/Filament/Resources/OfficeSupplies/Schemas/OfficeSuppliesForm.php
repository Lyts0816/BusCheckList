<?php

namespace App\Filament\Resources\OfficeSupplies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;


class OfficeSuppliesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Item Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->rule('regex:/^[A-Za-z\s]+$/')
                    ->validationMessages([
                        'required' => 'Item name is required',
                        'maxValue' => 'Item name cannot be greater than 50 characters.',
                        'unique' => 'Item name already exists.',
                        'regex' => 'The item name may only contain letters and spaces.',
                    ]),

                // TextInput::make('brand')
                //     ->label('Brand')
                //     ->required()
                //     ->maxLength(100)
                //     ->validationMessages([
                //         'required' => 'Brand is required',
                //     ]),
                    

                Select::make('category')
                    ->label('Category')
                    ->required()
                    ->options([
                        'Batteries' => 'Batteries',
                        'RAM' => 'RAM',
                        'SSD' => 'SSD',
                        'HDD' => 'HDD',
                        'External Storage' => 'External Storage',
                        'Cables' => 'Cables',
                        'Other' => 'Other',
                    ])
                    ->validationMessages([
                        'required' => 'Category is required',
                    ]),

                Select::make('unit')
                    ->label('Unit of Measurement')
                    ->required()
                    ->options([
                        'PCS' => 'PCS',
                        'BOX' => 'BOX',
                        'PACK' => 'PACK',
                        'REAM' => 'REAM',
                        'SET' => 'SET',
                        'ROLL' => 'ROLL',
                        'PAIR' => 'PAIR',
                        'CASE' => 'CASE',
                    ]),

                TextInput::make('stock')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->maxValue(999)
                    ->required(),

            ]);
    }
}
