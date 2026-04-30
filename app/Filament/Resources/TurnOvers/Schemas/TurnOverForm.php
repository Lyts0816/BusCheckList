<?php

namespace App\Filament\Resources\TurnOvers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TurnOverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from_department')
                    ->required()
                    ->maxLength(100),

                TextInput::make('to_department')
                    ->required()
                    ->maxLength(100),

                DatePicker::make('current_date')
                    ->required(),

                DatePicker::make('printed_date')
                    ->required(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric(),

                TextInput::make('particulars')
                    ->required()
                    ->maxLength(100),

                TextInput::make('serial_number')
                    ->maxLength(100),

                TextInput::make('recipient')
                    ->maxLength(100),

                TextInput::make('recipient_department_head')
                    ->maxLength(100),

                TextInput::make('endorser')
                    ->maxLength(100),

                TextInput::make('endorser_department_head')
                    ->maxLength(100),
            ]);
    }
}
