<?php

namespace App\Filament\Resources\Shipments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ShipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->dense()
                    ->schema([

                        Section::make('')
                            ->schema([
                                TextInput::make('tracking_number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1),

                                TextInput::make('barcode')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1),

                                TextInput::make('or_number')
                                    ->label('OR Number')
                                    ->columnSpan(1),

                                Select::make('status')
                                    ->options([
                                        'created' => 'Created',
                                        'in_transit' => 'In Transit',
                                        'arrived' => 'Arrived',
                                        'claimed' => 'Claimed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('origin_terminal')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('destination_terminal')
                                    ->required()
                                    ->columnSpan(2),

                            ])->columnSpanFull()->columns(4)->compact(),

                        Section::make('')
                            ->schema([
                                TextInput::make('sender_name')->required()
                                    ->columnSpan(3),

                                TextInput::make('sender_contact')
                                    ->columnSpan(1),

                                Textarea::make('sender_address')
                                    ->columnSpanFull(),

                            ])->columns(4)->compact()->columnSpan(4),

                        Section::make('')
                            ->schema([
                                TextInput::make('recipient_name')->required()
                                    ->columnSpan(3),

                                TextInput::make('recipient_contact')
                                    ->columnSpan(1),

                                Textarea::make('recipient_address')
                                    ->columnSpanFull(),

                            ])->columns(4)->compact()->columnSpan(4),

                        Section::make('')
                            ->schema([
                                TextInput::make('box_number')
                                    ->columnSpan(1.5),

                                TextInput::make('quantity')
                                    ->columnSpan(1.5)
                                    ->numeric()
                                    ->default(1),

                                TextInput::make('weight')
                                    ->columnSpan(1)
                                    ->numeric(),

                                Textarea::make('description')
                                    ->columnSpanFull(),
                            ])->columns(4)->compact()->columnSpanFull(),

                    ])->columns(8)->columnSpanFull(),

            ]);
    }
}
