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
                        'HR' => 'HR',
                        'Operations' => 'Operations',
                        'Production' => 'Production',
                        'Accounting' => 'Accounting',
                        'Cash' => 'Cash',
                        'Clinic' => 'Clinic',
                    ]),
                TextInput::make('printer_host')
                    ->label('Printer Host')
                    ->unique(ignoreRecord: true),  
                TextInput::make('printer_model')
                    ->nullable(),
                TextInput::make('printer_asset_code')
                    ->nullable(),
                TextInput::make('printer_serial_number')
                    ->required()
                    ->unique(ignoreRecord: true),
                DatePicker::make('date_acquired')
                    ->label('Date Acquired')
                    ->nullable(),
                TextInput::make('description'),
            ]);
    }
}
