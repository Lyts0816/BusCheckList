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
                Select::make('department')
                    ->required()
                    ->options([
                        'MIS' => 'MIS',
                        'STOCK ROOM' => 'STOCK ROOM',
                        'HR' => 'HR',
                        'Operations' => 'Operations',
                        'Production' => 'Production',
                        'Accounting' => 'Accounting',
                        'Cash' => 'Cash',
                        'Clinic' => 'Clinic',
                        'TERMINAL' => 'TERMINAL',
                        'SATELITE OFFICE GENSAN' => 'SATELITE OFFICE GENSAN',

                    ]),

                TextInput::make('printer_host')
                    ->label('Printer Host')
                    ->unique(ignoreRecord: true),

                TextInput::make('asset_code')
                    ->label('Asset Code')
                    ->nullable(),

                TextInput::make('printer_serial_number')
                    ->required()
                    ->helperText('If printer does not have a serial number, please input (NOSN + asset code, if no asset code, please input (NOSN + department name). Example: NOSN-MIS)')
                    ->label('Printer Serial Number')
                    ->unique(ignoreRecord: true),

                TextInput::make('printer_model')
                    ->nullable(),

                DatePicker::make('date_aquired')
                    ->label('Date Acquired')
                    ->nullable(),
                    
                TextInput::make('description'),
            ]);
    }
}
