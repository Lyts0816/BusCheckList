<?php

namespace App\Filament\Resources\AssignedComputers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssignedComputerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('system_unit_id')
                    ->label('System Unit')
                    ->relationship('systemUnit', 'serial_number') // or 'asset_code' or 'model'
                    ->searchable()
                    ->required(),

                Select::make('keyboard_id')
                    ->label('Keyboard')
                    ->relationship('keyboard', 'serial_number')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('mouse_id')
                    ->label('Mouse')
                    ->relationship('mouse', 'serial_number')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('monitor_id')
                    ->label('Monitor')
                    ->relationship('monitor', 'serial_number')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('ups_id')
                    ->label('UPS')
                    ->relationship('ups', 'serial_number')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('assigned_to')
                    ->label('Assigned To')
                    ->required(),

                DatePicker::make('assigned_date')
                    ->label('Assigned Date')
                    ->default(now())
                    ->required(),
            ]);
    }
}
