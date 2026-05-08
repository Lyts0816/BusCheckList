<?php

namespace App\Filament\Resources\SystemUnits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use PhpOffice\PhpSpreadsheet\Helper\Size;
use Filament\Support\Enums\TextSize;

class SystemUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Assignment')
                    ->schema([
                        TextEntry::make('id')
                            ->size(TextSize::ExtraSmall)
                            ->label('ID')
                            ->columnSpan(1),

                        TextEntry::make('assignedComputer.assigned_to')
                            ->size(TextSize::ExtraSmall)
                            ->label('Assigned To')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : 'Unassigned')
                            ->columnSpan(1),

                        TextEntry::make('assignedComputer.department_name')
                            ->size(TextSize::ExtraSmall)
                            ->label('Department')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : 'No Department')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->columnSpan(6)
                    ->compact(),

                Section::make('System Information')
                    ->schema([
                        TextEntry::make('asset_code')
                            ->size(TextSize::ExtraSmall)
                            ->label('Asset Code'),

                        TextEntry::make('serial_number')
                            ->size(TextSize::ExtraSmall)
                            ->label('Serial Number'),

                        TextEntry::make('ip_address')
                            ->size(TextSize::ExtraSmall)
                            ->label('IP Address')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('model')
                            ->size(TextSize::ExtraSmall)
                            ->label('Model'),

                        TextEntry::make('date_aquired')
                            ->size(TextSize::ExtraSmall)
                            ->label('Date Acquired')
                            ->date(),

                        TextEntry::make('date_aquired')
                            ->size(TextSize::ExtraSmall)
                            ->label('Years in Service')
                            ->formatStateUsing(function ($state): string {
                                if (! $state) {
                                    return 'N/A';
                                }

                                $diff = \Carbon\Carbon::parse($state)->diff(now());

                                $years = $diff->y;
                                $months = $diff->m;

                                $yearLabel = $years === 1 ? 'year' : 'years';
                                $monthLabel = $months === 1 ? 'month' : 'months';

                                return "{$years} {$yearLabel}, {$months} {$monthLabel}";
                            }),
                    ])
                    ->columns(1)
                    ->columnSpan(3)
                    ->compact(),

                Section::make('Specifications')
                    ->schema([
                        TextEntry::make('OS')
                            ->size(TextSize::ExtraSmall)
                            ->label('OS')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('windows_serial_number')
                            ->size(TextSize::ExtraSmall)
                            ->label('Windows Serial Number')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('microsoft_serial_number')
                            ->size(TextSize::ExtraSmall)
                            ->label('Microsoft Serial Number')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('ram')
                            ->size(TextSize::ExtraSmall)
                            ->label('RAM')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('storage')
                            ->size(TextSize::ExtraSmall)
                            ->label('Storage')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),

                        TextEntry::make('processor')
                            ->size(TextSize::ExtraSmall)
                            ->label('Processor')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),
                    ])
                    ->columns(1)
                    ->columnSpan(3)
                    ->compact(),

                Section::make()
                    ->schema([
                        TextEntry::make('description')
                            ->size(TextSize::ExtraSmall)
                            ->label('Description')
                            ->formatStateUsing(fn ($state) => filled($state) ? $state : '-'),
                    ])
                    ->columnSpan(6)
                    ->compact(),
            ])->columns(6);
    }
}
