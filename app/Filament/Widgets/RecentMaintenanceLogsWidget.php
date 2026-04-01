<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use App\Models\SystemUnit;
use App\Models\Printer;
use App\Models\Peripherals;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMaintenanceLogsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Maintenance Activities';

    protected int|string|array $columnSpan = 12;

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(AssetMaintenanceLog::query()->latest('maintenance_date')->limit(10))
            ->columns([
                TextColumn::make('maintainable_type')
                    ->label('Asset Type')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            SystemUnit::class => 'System Unit',
                            Printer::class => 'Printer',
                            Peripherals::class => 'Peripheral',
                            default => $state,
                        };
                    }),

                TextColumn::make('maintainable_id')
                    ->label('Asset Code')
                    ->formatStateUsing(function ($state, AssetMaintenanceLog $record) {
                        if ($record->maintainable_type === SystemUnit::class) {
                            $asset = SystemUnit::find($state);
                            return $asset ? $asset->asset_code : 'N/A';
                        } elseif ($record->maintainable_type === Printer::class) {
                            $asset = Printer::find($state);
                            return $asset ? $asset->asset_code : 'N/A';
                        } elseif ($record->maintainable_type === Peripherals::class) {
                            $asset = Peripherals::find($state);
                            return $asset ? $asset->name : 'N/A';
                        }
                        return 'N/A';
                    }),

                TextColumn::make('maintenance_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),

                TextColumn::make('maintenance_date')
                    ->label('Date')
                    ->dateTime('M d, Y')
                    ->sortable(),

                TextColumn::make('performed_by')
                    ->label('Performed By')
                    ->limit(20),

                TextColumn::make('cost')
                    ->label('Cost')
                    ->formatStateUsing(fn ($state) => '₱' . number_format($state, 2))
                    ->alignment('right'),
            ])
            ->paginated(false);
    }
}
