<?php

namespace App\Filament\Resources\SupplyTransactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;

class SupplyTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('type')
                ->options([
                    'IN' => 'IN',
                    'OUT' => 'OUT',
                    'ADJUSTMENT' => 'ADJUSTMENT',
                ])
                ->required(),

            Select::make('department')
                ->options([
                    'Accounting' => 'Accounting',
                    'Operations' => 'Operations',
                    'HR' => 'HR',
                    // add other departments
                ])
                ->required(),

            TextInput::make('user')
            ->required(),

            Textarea::make('remarks'),

            Repeater::make('items')
                ->relationship('items') // connect to items
                ->schema([
                    Select::make('supply_id')
                        ->label('Supply')
                        ->relationship('supply', 'name') // pulls name from supply table
                        ->required(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->required(),
                ])
                ->collapsible(),
            ]);
    }
}
