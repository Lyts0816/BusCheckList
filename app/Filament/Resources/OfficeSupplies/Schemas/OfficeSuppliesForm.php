<?php

namespace App\Filament\Resources\OfficeSupplies\Schemas;

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
                    ->validationMessages([
                        'required' => 'Item name is required',
                        'maxValue' => 'Item name cannot be greater than 50 characters.',
                        'unique' => 'Item name already exists.',
                    ]),
                    

                TextInput::make('category')
                    ->label('Category')
                    ->required(),

                TextInput::make('unit')
                    ->label('Unit of Measurement')
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->maxValue(999)
                    ->required(),

            ]);
    }
}
