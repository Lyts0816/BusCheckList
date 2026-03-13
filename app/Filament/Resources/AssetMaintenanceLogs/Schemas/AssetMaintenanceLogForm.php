<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

class AssetMaintenanceLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make()
                    // ->gap(false)
                    ->dense()
                    ->schema([

                        Select::make('maintainable_type')
                            ->label('Asset Type')
                            ->options([
                                \App\Models\SystemUnit::class => 'System Unit',
                                \App\Models\Printer::class => 'Printer',
                                \App\Models\Peripherals::class => 'Peripheral',
                            ])
                            ->required()
                            ->reactive()
                            ->columnSpan(2),

                        Select::make('maintainable_id')
                            ->label('Asset')
                            ->options(function (callable $get) {

                                $type = $get('maintainable_type');

                                if (!$type) {
                                    return [];
                                }

                                return $type::pluck('asset_code', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->columnSpan(2),

                        Select::make('component_id')
                            ->label('Component')
                            ->options(function (callable $get) {

                                $type = $get('maintainable_type');

                                if (!$type) {
                                    return [];
                                }

                                $assetTypeMap = [
                                    \App\Models\SystemUnit::class => 'system_unit',
                                    \App\Models\Printer::class => 'printer',
                                    \App\Models\Peripherals::class => 'peripheral',
                                ];

                                return \App\Models\Component::query()
                                    ->where('asset_type', $assetTypeMap[$type])
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->columnSpan(2),

                        Select::make('maintenance_type')
                            ->options([
                                'Cleaning' => 'Cleaning',
                                'repair' => 'Repair',
                                'upgrade' => 'Upgrade',
                                'replacement' => 'Replacement',
                            ])
                            ->required()
                            ->columnSpan(2),

                        DatePicker::make('maintenance_date')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('cost')
                            ->numeric()
                            ->default(0)
                            ->prefix('₱')
                            ->columnSpan(2),

                        TextInput::make('performed_by')
                            ->columnSpan(6),

                        // DatePicker::make('next_maintenance'),

                        Textarea::make('issue_reported')
                            ->columnSpanFull(),

                        Textarea::make('action_taken')
                            ->columnSpanFull(),

                        Textarea::make('remarks')
                            ->columnSpanFull(),

                    ])->columns(6)->columnSpanFull()

            ]);
    }
}
