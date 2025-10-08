<?php

namespace App\Filament\Resources\AssignedComputers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssignedComputerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('assigned_to'),
                TextEntry::make('department'),

                Section::make('System Unit')
                    ->schema([
                        TextEntry::make('systemUnit.asset_code')->label('Asset Code'),
                        TextEntry::make('systemUnit.serial_number')->label('Serial Number'),
                        TextEntry::make('systemUnit.model')->label('Model'),
                        TextEntry::make('systemUnit.date_aquired')->label('Date Acquired')->date(),
                        TextEntry::make('systemUnit.OS')->label('Operating System'),
                        TextEntry::make('systemUnit.windows_serial_number')->label('Windows Serial Number'),
                        TextEntry::make('systemUnit.microsoft_serial_number')->label('Microsoft Serial Number'),
                        TextEntry::make('systemUnit.ram')->label('RAM'),
                        TextEntry::make('systemUnit.storage')->label('Storage'),
                        TextEntry::make('systemUnit.processor')->label('Processor'),
                        TextEntry::make('systemUnit.ip_address')->label('IP Address'),
                        TextEntry::make('systemUnit.description')->label('Description'),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
                    ->compact(),

                Section::make('Monitor')
                    ->schema([
                        TextEntry::make('monitor.asset_code')->label('Asset Code'),
                        TextEntry::make('monitor.serial_number')->label('Serial Number'),
                        TextEntry::make('monitor.model')->label('Model'),
                        TextEntry::make('monitor.date_acquired')->label('Date Acquired')->date(),
                    ])
                    ->columns(2)
                    ->compact(),

                Section::make('Keyboard')
                    ->schema([
                        TextEntry::make('keyboard.asset_code')->label('Asset Code'),
                        TextEntry::make('keyboard.serial_number')->label('Serial Number'),
                        TextEntry::make('keyboard.model')->label('Model'),
                        TextEntry::make('keyboard.date_acquired')->label('Date Acquired')->date(),
                    ])
                    ->columns(2)
                    ->compact(),

                Section::make('Mouse')
                    ->schema([
                        TextEntry::make('mouse.asset_code')->label('Asset Code'),
                        TextEntry::make('mouse.serial_number')->label('Serial Number'),
                        TextEntry::make('mouse.model')->label('Model'),
                        TextEntry::make('mouse.date_acquired')->label('Date Acquired')->date(),
                    ])
                    ->columns(2)
                    ->compact(),

                Section::make('UPS')
                    ->schema([
                        TextEntry::make('ups.asset_code')->label('Asset Code'),
                        TextEntry::make('ups.serial_number')->label('Serial Number'),
                        TextEntry::make('ups.model')->label('Model'),
                        TextEntry::make('ups.date_acquired')->label('Date Acquired')->date(),
                    ])
                    ->columns(2)
                    ->compact(),
            ]);

    }
}
