<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use App\Models\SystemUnit;
use App\Models\Printer;
use App\Models\Peripherals;
use App\Models\OfficeSupplies;

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
                                SystemUnit::class => 'System Unit',
                                Printer::class => 'Printer',
                                Peripherals::class => 'Peripheral',
                            ])
                            ->required()
                            ->reactive()
                            ->columnSpan(2),

                        Select::make('maintainable_id')
                            ->label('Asset')
                            ->options(function (Get $get): array {

                                $type = $get('maintainable_type');

                                if (!$type) {
                                    return [];
                                }

                                return $type::query()
                                    ->orderBy('asset_code')
                                    ->get()
                                    ->mapWithKeys(function ($asset) use ($type) {
                                        $baseLabel = $asset->asset_code ?: 'Asset #' . $asset->id;

                                        $serial = null;
                                        if ($type === \App\Models\SystemUnit::class) {
                                            $serial = $asset->serial_number ?? null;
                                        } elseif ($type === \App\Models\Printer::class) {
                                            $serial = $asset->printer_serial_number ?? null;
                                        } elseif ($type === \App\Models\Peripherals::class) {
                                            $serial = $asset->serial_number ?? null;
                                        }

                                        $label = $serial ? $baseLabel . ' — ' . $serial : $baseLabel;

                                        return [$asset->id => $label];
                                    })
                                    ->all();
                            })
                            ->searchable()
                            ->required()
                            ->columnSpan(2),

                        Select::make('component_id')
                            ->label('Component')
                            ->options(function (Get $get): array {

                                $type = $get('maintainable_type');

                                if (!$type) {
                                    return [];
                                }

                                $assetTypeMap = [
                                    SystemUnit::class => 'system_unit',
                                    Printer::class => 'printer',
                                    Peripherals::class => 'peripheral',
                                ];

                                return \App\Models\Component::query()
                                        ->where('asset_type', '=', $assetTypeMap[$type])
                                        ->orderBy('name', 'asc')
                                    ->get()
                                    ->mapWithKeys(function ($component) {
                                        $label = $component->name ?: 'Component #' . $component->id;

                                        return [$component->id => $label];
                                    })
                                    ->all();
                            })
                            ->searchable()
                            ->columnSpan(2),

                        Select::make('maintenance_type')
                            ->options([
                                'preventive' => 'Preventive',
                                'cleaning' => 'Cleaning',
                                'repair' => 'Repair',
                                'upgrade' => 'Upgrade',
                                'replacement' => 'Replacement',
                            ])
                            ->required()
                            ->live()
                            ->columnSpan(2),

                        Select::make('office_supply_id')
                            ->label('Replacement Item')
                            ->options(function () {
                                return OfficeSupplies::query()
                                    ->orderBy('name', 'asc')
                                    ->get()
                                    ->mapWithKeys(function ($supply) {
                                        $baseName = $supply->name ?: 'Supply #' . $supply->id;

                                        $label = $supply->brand
                                            ? $baseName . ' (' . $supply->brand . ')'
                                            : $baseName;

                                        return [$supply->id => $label];
                                    })
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn(callable $get): bool => $get('maintenance_type') === 'replacement')
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
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->columnSpan(6),

                        Textarea::make('issue_reported')
                            ->columnSpan('full'),

                        Textarea::make('action_taken')
                            ->columnSpan('full'),

                        Textarea::make('remarks')
                            ->columnSpan('full'),

                    ])->columns(6)->columnSpan('full')

            ]);
    }
}
