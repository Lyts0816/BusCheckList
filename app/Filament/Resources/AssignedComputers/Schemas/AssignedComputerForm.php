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

                Select::make('department')
                    ->required()
                    ->options([
                        'MIS' => 'MIS',
                        'HR' => 'HR',
                        'Operations' => 'Operations',
                        'Production' => 'Production',
                        'Accounting' => 'Accounting',
                        'Cash' => 'Cash',
                        'Clinic' => 'Clinic',
                    ]),

                Select::make('system_unit_id')
                    ->label('System Unit')
                    ->relationship('systemUnit', 'serial_number', function ($query) {
                        return $query->orderBy('serial_number', 'desc');
                    })
                    ->preload()
                    ->searchable()
                    ->searchPrompt('Search system unit by serial number...')
                    ->required()
                    ->unique(
                        table: 'assigned_computers',
                        column: 'system_unit_id',
                        ignoreRecord: true
                    )
                    ->validationMessages([
                        'unique' => 'This system unit is already assigned to another user.',
                    ]),

                Select::make('keyboard_id')
                    ->label('Keyboard')
                    ->relationship('keyboard', 'serial_number', fn($query) => $query->where('item_type', 'Keyboard')->orderBy('id', 'desc'))
                    ->searchable()
                    ->searchPrompt('Search keyboard by serial number...')
                    ->preload()
                    ->nullable(),

                Select::make('mouse_id')
                    ->label('Mouse')
                    ->relationship('mouse', 'serial_number', fn($query) => $query->where('item_type', 'Mouse')->orderBy('id', 'desc'))
                    ->searchable()
                    ->searchPrompt('Search mouse by serial number...')
                    ->preload()
                    ->nullable(),

                Select::make('monitor_id')
                    ->label('Monitor')
                    ->relationship('monitor', 'serial_number', fn($query) => $query->where('item_type', 'Monitor')->orderBy('id', 'desc'))
                    ->searchable()
                    ->searchPrompt('Search monitor by serial number...')
                    ->preload()
                    ->nullable(),

                Select::make('ups_id')
                    ->label('UPS')
                    ->relationship('ups', 'serial_number', fn($query) => $query->where('item_type', 'UPS')->orderBy('id', 'desc'))
                    ->searchable()
                    ->searchPrompt('Search UPS by serial number...')
                    ->preload()
                    ->nullable(),
            ]);
    }
}
