<?php

namespace App\Filament\Resources\Transfers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;


class TransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),

                TextInput::make('from')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(50)
                    ->required(),

                TextInput::make('to')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(50)
                    ->required(),

                TextInput::make('prepared_by')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(30)
                    ->required(),

                TextInput::make('guard_on_duty')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(30),

                TextInput::make('received_by')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(30),


                Repeater::make('items')
                    ->relationship()
                    ->schema([

                        TextInput::make('item_name')
                            ->required(),

                        TextInput::make('asset_code'),

                        TextInput::make('serial_number'),

                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->addActionLabel('Add Item')
                    ->collapsible()
                    ->reorderableWithButtons()
                    ->required(),
            ]);
    }
}
