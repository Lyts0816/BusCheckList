<?php

namespace App\Filament\Resources\Peripherals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class PeripheralsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_type')
                    ->options([
                        'Keyboard' => 'Keyboard',
                        'Mouse' => 'Mouse',
                        'Monitor' => 'Monitor',
                        'UPS' => 'UPS',
                        'Headset' => 'Headset',
                        'Webcam' => 'Webcam',
                        'Charger' => 'Charger',
                        'Docking Station' => 'Docking Station',
                        'Microphone/Speaker' => 'Microphone/Speaker',
                        'Router' => 'Router',
                        'Switch' => 'Switch',
                        'Projector' => 'Projector',
                        'Other' => 'Other',
                    ])
                    ->required(),
                TextInput::make('asset_code'),
                TextInput::make('serial_number')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('model'),
                DatePicker::make('date_acquired')
                    ->default('N/A')
                    ->helperText('Leave blank if date aquired date is not available'),
                TextInput::make('description'),
            ]);
    }
}
