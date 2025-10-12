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
                TextInput::make('asset_code'),
                TextInput::make('serial_number')
                ->unique(ignoreRecord: true),
                TextInput::make('model'),
                DatePicker::make('date_aquired'),
                Select::make('OS')
                    ->label('OS')
                    ->options([
                        'Windows 11 Pro' => 'Windows 11 Pro',
                        'Windows 10 Pro' => 'Windows 10 Pro',
                        'Windows 8.1 Pro' => 'Windows 8.1',
                        'Windows 7 Pro' => 'Windows 7',
                        'Windows Server 2019' => 'Windows Server 2019',
                        'Windows Server 2022' => 'Windows Server 2022',
                        'Other' => 'Other',
                    ]),
                TextInput::make('windows_serial_number'),
                TextInput::make('microsoft_serial_number'),
                TextInput::make('ram')
                    ->label('RAM'),
                TextInput::make('storage')
                    ->label('Storage'),
                Select::make('processor')
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
                ]),
                TextInput::make('ip_address')
                    ->label('IP Address'),
                TextInput::make('description'),
            ]);
    }
}
