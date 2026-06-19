<?php

namespace App\Filament\Resources\Transfers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;


class TransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('date')
                    ->default(now())
                    ->seconds(false)
                    ->format('d-m-Y h:i A')
                    ->required()
                    ->columnSpan(1),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Transferred' => 'Transferred',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->default('Transferred')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('from')
                    ->default('MIS')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->maxLength(50)
                    ->required()
                    ->columnSpan(2),

                TextInput::make('to')
                    ->autofocus()
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->maxLength(50)
                    ->required()
                    ->columnSpan(1),

                TextInput::make('prepared_by')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->datalist([
                        'MACRHYS RHYANNE MONDEJAR',
                        'MARK TOMAS',
                    ])
                    ->maxLength(30)
                    ->required()
                    ->columnSpan(1),

                TextInput::make('guard_on_duty')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->maxLength(30)
                    ->columnSpan(1),

                TextInput::make('received_by')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->maxLength(30)
                    ->columnSpan(1),


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

            ])->columns(4);
    }
}
