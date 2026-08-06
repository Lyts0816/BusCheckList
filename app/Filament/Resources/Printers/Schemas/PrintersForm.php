<?php

namespace App\Filament\Resources\Printers\Schemas;

use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class PrintersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Good Condition' => 'Good Condition',
                        'In Maintenance' => 'In Maintenance',
                        'For Repair' => 'For Repair',
                        'Damaged' => 'Damaged',
                        'Lost' => 'Lost',
                        'Retire' => 'Retire',
                        'Spare' => 'Spare',
                    ])
                    ->default('Good Condition')
                    ->required(),

               Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                TextInput::make('printer_host')
                    ->label('Printer Host')
                    ->maxLength(30)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->unique(ignoreRecord: true),

                TextInput::make('asset_code')
                    ->label('Asset Code')
                    ->maxLength(50)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->nullable(),

                TextInput::make('printer_serial_number')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(50)
                    ->required()
                    ->helperText('If printer does not have a serial number, please input (NOSN + asset code, if no asset code, please input (NOSN + department name). Example: NOSN-MIS)')
                    ->label('Printer Serial Number')
                    ->unique(ignoreRecord: true),

                TextInput::make('printer_model')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->maxLength(50)
                    ->nullable(),

                DatePicker::make('date_aquired')
                    ->label('Date Acquired')
                    ->nullable(),
                    
                TextInput::make('description')
                    ->maxLength(255),
            ]);
    }
}
