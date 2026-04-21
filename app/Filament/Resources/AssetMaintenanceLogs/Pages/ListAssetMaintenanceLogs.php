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
                ->label('Create Maintenance Log'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'ALL' => Tab::make()
                ->label('All Maintenance Logs')
                ->modifyQueryUsing(function ($query) {
                    $query->whereNotNull('id');
                })
                ->badge(fn () => AssetMaintenanceLog::count()),

            'SYSTEM UNITS' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [SystemUnit::class]);
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [SystemUnit::class])->count()),

            'UPS' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['UPS']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['UPS']);
                })->count()),

            'MONITOR' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['MONITOR']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['MONITOR']);
                })->count()),

            'KEYBOARD' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['KEYBOARD']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['KEYBOARD']);
                })->count()),

            'MOUSE' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    $query->whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                        $morphQuery->whereRaw('UPPER(item_type) = ?', ['MOUSE']);
                    });
                })
                ->badge(fn () => AssetMaintenanceLog::whereHasMorph('maintainable', [Peripherals::class], function ($morphQuery) {
                    $morphQuery->whereRaw('UPPER(item_type) = ?', ['MOUSE']);
                })->count()),
        ];
    }
}
