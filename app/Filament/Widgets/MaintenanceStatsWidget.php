<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MaintenanceStatsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 12;

    protected function getStats(): array
    {
        $totalLogs = AssetMaintenanceLog::count();
        $thisMonthLogs = AssetMaintenanceLog::whereMonth('maintenance_date', now()->month)
            ->whereYear('maintenance_date', now()->year)
            ->count();
        $totalCost = AssetMaintenanceLog::sum('cost');
        $averageCost = $totalLogs > 0 ? round($totalCost / $totalLogs, 2) : 0;

        return [
            Stat::make('Total Maintenance Logs', $totalLogs)
                ->description('All time records')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('This Month', $thisMonthLogs)
                ->description('Maintenance activities')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Total Cost', '₱' . number_format($totalCost, 2))
                ->description('All maintenance expenses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Average Cost', '₱' . number_format($averageCost, 2))
                ->description('Per maintenance log')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}
