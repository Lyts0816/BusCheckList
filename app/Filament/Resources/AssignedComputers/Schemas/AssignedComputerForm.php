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
                TextInput::make('assigned_to')
                    ->label('Assigned To')
                    ->required(),

                TextInput::make('department')
                    ->label('Department')
                    ->required(),

                Select::make('system_unit_id')
                    ->label('System Unit')
                    ->relationship('systemUnit', 'serial_number')
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('keyboard_id')
                    ->label('Keyboard')
                    ->relationship('keyboard', 'serial_number', fn ($query) => $query->where('item_type', 'Keyboard'))
                    ->searchable()
                    ->preload()
                    ->nullable(),       

                Select::make('mouse_id')
                    ->label('Mouse')
                    ->relationship('mouse', 'serial_number', fn ($query) => $query->where('item_type', 'Mouse'))
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('monitor_id')
                    ->label('Monitor')
                    ->relationship('monitor', 'serial_number', fn ($query) => $query->where('item_type', 'Monitor'))
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('ups_id')
                    ->label('UPS')
                    ->relationship('ups', 'serial_number', fn ($query) => $query->where('item_type', 'UPS'))
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
