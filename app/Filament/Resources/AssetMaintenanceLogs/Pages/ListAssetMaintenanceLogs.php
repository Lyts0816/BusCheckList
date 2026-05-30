<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Pages;

use App\Filament\Resources\AssetMaintenanceLogs\AssetMaintenanceLogResource;
use App\Models\AssetMaintenanceLog;
use App\Models\Peripherals;
use App\Models\SystemUnit;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListAssetMaintenanceLogs extends ListRecords
{
    protected static string $resource = AssetMaintenanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Maintenance Log')
                ->modalWidth('7xl'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'ALL' => Tab::make('ALL')
                ->label('All Maintenance Logs')
                ->modifyQueryUsing(function ($query) {
                    $query->whereNotNull('id');
                })
                ->badge(fn () => AssetMaintenanceLog::count('*')),

            'SYSTEM UNITS' => Tab::make('SYSTEM UNITS')
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [SystemUnit::class], fn($q) => $q);
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [SystemUnit::class], fn($q) => $q)->count('*')),

            'UPS' => Tab::make('UPS')
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['UPS']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['UPS']);
                })->count('*')),

            'MONITOR' => Tab::make('MONITOR')
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['MONITOR']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['MONITOR']);
                })->count('*')),

            'KEYBOARD' => Tab::make('KEYBOARD')
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['KEYBOARD']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['KEYBOARD']);
                })->count('*')),

            'MOUSE' => Tab::make('MOUSE')
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['MOUSE']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['MOUSE']);
                })->count('*')),
        ];
    }
}
