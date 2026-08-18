<?php

namespace App\Filament\Resources\Peripherals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

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
                        'CCTV' => 'CCTV',
                        'BUS CCTV' => 'BUS CCTV',
                        'BUS SSD' => 'BUS SSD',
                        'BUS DVR' => 'BUS DVR',
                        'Other' => 'Other',
                    ])
                    ->required(),

                TextInput::make('asset_code')
                    ->maxLength(50)
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),

                TextInput::make('serial_number')
                    ->maxLength(50)
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('model')
                    ->maxLength(50)
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),

                Select::make('status')
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

                DatePicker::make('date_acquired')
                    ->default('N/A')
                    ->helperText('Leave blank if date aquired date is not available'),

                FileUpload::make('image')
                    ->label('Peripheral Image')
                    ->image()
                    ->imageEditor()
                    ->imagePreviewHeight('200')
                    ->directory('peripherals')
                    ->disk('public')
                    ->maxSize(8048) // 2 MB
                    ->columnSpanFull(),

                TextInput::make('description')
                    ->maxLength(255),
            ]);
    }
}
