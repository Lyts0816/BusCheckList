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
                    ->required(),

                TextInput::make('category')
                    ->label('Category')
                    ->required(),

                TextInput::make('unit')
                    ->label('Unit of Measurement')
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->required(),

            ]);
    }
}
