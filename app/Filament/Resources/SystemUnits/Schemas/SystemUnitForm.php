<?php

namespace App\Filament\Resources\SystemUnits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SystemUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('asset_code')
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->columnSpan(2)
                    ->maxLength(50),

                TextInput::make('serial_number')
                    ->columnSpan(2)
                    ->maxLength(50)
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->unique(ignoreRecord: true),

                Select::make('asset_type')
                    ->columnSpan(2)
                    ->label('Asset Type')
                    ->options([
                        'System Unit' => 'System Unit',
                        'Laptop' => 'Laptop',
                    ]),

                TextInput::make('model')
                    ->maxLength(50)
                    ->columnSpan(2)
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),

                DatePicker::make('date_aquired')
                    ->columnSpan(2)
                    ->helperText('Leave blank if date aquired date is not available'),

                Select::make('OS')
                    ->columnSpan(2)
                    ->label('Operating System')
                    ->options([
                        'Windows 11 Pro' => 'Windows 11 Pro',
                        'Windows 10 Pro' => 'Windows 10 Pro',
                        'Windows 8 Pro' => 'Windows 8 Pro',
                        'Windows 7 Pro' => 'Windows 7 Pro',
                        'Windows Server 2019' => 'Windows Server 2019',
                        'Windows Server 2022' => 'Windows Server 2022',
                        'Other' => 'Other',
                        'Cant find OS' => 'Cant find OS',
                    ]),

                TextInput::make('windows_serial_number')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->columnSpan(3)
                    ->maxLength(50),

                TextInput::make('microsoft_serial_number')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->columnSpan(3)
                    ->maxLength(50),

                Select::make('status')
                    ->columnSpan(2)
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

                TextInput::make('ram')
                    ->columnSpan(2)
                    ->maxLength(20)
                    ->label('RAM')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->datalist([
                        '2GB',
                        '4GB',
                        '8GB',
                        '16GB',
                        '32GB',
                        '64GB',
                        '128GB',
                        '256GB',
                    ]),

                TextInput::make('storage')
                    ->columnSpan(2)
                    ->maxLength(20)
                    ->label('Storage')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),

                Select::make('processor')
                    ->columnSpan(3)
                    ->label('Processor')
                    ->formatStateUsing(fn (?string $state): ?string => $state === null ? null : strtoupper($state))
                    ->dehydrateStateUsing(fn (?string $state): ?string => $state === null ? null : strtoupper($state))
                    ->options([
                        'INTEL CORE I3' => 'Intel Core i3',
                        'INTEL CORE I5' => 'Intel Core i5',
                        'INTEL CORE I7' => 'Intel Core i7',
                        'INTEL CORE I9' => 'Intel Core i9',
                        'INTEL PENTIUM' => 'Intel Pentium',
                        'INTEL CELERON' => 'Intel Celeron',
                        'INTEL ATOM' => 'Intel Atom',
                        'AMD RYZEN 3' => 'AMD Ryzen 3',
                        'AMD RYZEN 5' => 'AMD Ryzen 5',
                        'AMD RYZEN 7' => 'AMD Ryzen 7',
                        'AMD RYZEN 9' => 'AMD Ryzen 9',
                        'AMD ATHLON' => 'AMD Athlon',
                        'AMD A-SERIES' => 'AMD A-Series',
                        'OTHER' => 'Other',
                        'CANT FIND PROCESSOR' => 'Cant find Processor',
                    ]),

                TextInput::make('ip_address')
                    ->maxLength(20)
                    ->label('IP Address')
                    ->columnSpan(3),

                TextInput::make('description')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])->columns(10);
    }
}
