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
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->columnSpan(2),

                TextInput::make('serial_number')
                    ->columnSpan(2)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->unique(ignoreRecord: true),

                Select::make('asset_type')
                    ->columnSpan(2)
                    ->label('Asset Type')
                    ->options([
                        'System Unit' => 'System Unit',
                        'Laptop' => 'Laptop',
                    ]),

                TextInput::make('model')
                    ->columnSpan(2)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state)),

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
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->columnSpan(4),

                TextInput::make('microsoft_serial_number')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->columnSpan(4),

                TextInput::make('ram')
                    ->columnSpan(2)
                    ->label('RAM')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
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
                    ->label('Storage')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state)),

                Select::make('processor')
                    ->columnSpan(3)
                    ->label('Processor')
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->options([
                        'Intel Core i3' => 'Intel Core i3',
                        'Intel Core i5' => 'Intel Core i5',
                        'Intel Core i7' => 'Intel Core i7',
                        'Intel Core i9' => 'Intel Core i9',
                        'Intel Pentium' => 'Intel Pentium',
                        'Intel Celeron' => 'Intel Celeron',
                        'Intel Atom' => 'Intel Atom',
                        'AMD Ryzen 3' => 'AMD Ryzen 3',
                        'AMD Ryzen 5' => 'AMD Ryzen 5',
                        'AMD Ryzen 7' => 'AMD Ryzen 7',
                        'AMD Ryzen 9' => 'AMD Ryzen 9',
                        'AMD Athlon' => 'AMD Athlon',
                        'AMD A-Series' => 'AMD A-Series',
                        'Other' => 'Other',
                        'Cant find Processor' => 'Cant find Processor',
                    ]),

                TextInput::make('ip_address')
                    ->label('IP Address')
                    ->columnSpan(3),

                TextInput::make('description')
                    ->columnSpanFull(),
            ])->columns(10);
    }
}
