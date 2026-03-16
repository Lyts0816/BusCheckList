<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_code')
                    ->label('Employee Code')
                    ->maxLength(255),

                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                Select::make('department')
                    ->options([
                        'HR' => 'HR',
                        'Operations' => 'Operations',
                        'MIS' => 'MIS',
                        'Production' => 'Production',
                        'Accounting' => 'Accounting',
                    ])
                    ->searchable()
                    ->preload(),

                TextInput::make('remaining_vl')
                    ->label('Remaining VL')
                    ->maxLength(255),

                TextInput::make('remaining_sl')
                    ->label('Remaining SL')
                    ->maxLength(255),

                TextInput::make('availed_vl')
                    ->label('Availed VL')
                    ->maxLength(255),

                TextInput::make('availed_sl')
                    ->label('Availed SL')
                    ->maxLength(255),

                TextInput::make('availed_wo_pay')
                    ->label('Availed W/O Pay')
                    ->maxLength(255),

                TextInput::make('availed_sss_sl')
                    ->label('Availed SSS SL')
                    ->maxLength(255),

                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
