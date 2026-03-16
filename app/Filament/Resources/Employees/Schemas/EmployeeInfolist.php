<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Employee Information')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID'),

                        TextEntry::make('employee_code')
                            ->label('Employee Code')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('full_name')
                            ->label('Full Name'),

                        TextEntry::make('department')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('remaining_vl')
                            ->label('Remaining VL')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('remaining_sl')
                            ->label('Remaining SL')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('availed_vl')
                            ->label('Availed VL')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('availed_sl')
                            ->label('Availed SL')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('availed_wo_pay')
                            ->label('Availed W/O Pay')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('availed_sss_sl')
                            ->label('Availed SSS SL')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('remarks')
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),
                    ])
                    ->columns(3),
            ]);
    }
}
