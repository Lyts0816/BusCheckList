<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class MaintenanceCostWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 6;

    protected function getStats(): array
    {
        $thisMonthCost = AssetMaintenanceLog::whereMonth('maintenance_date', now()->month)
            ->whereYear('maintenance_date', now()->year)
            ->sum('cost');

        $lastMonthCost = AssetMaintenanceLog::whereMonth('maintenance_date', now()->subMonth()->month)
            ->whereYear('maintenance_date', now()->subMonth()->year)
            ->sum('cost');

        $costTrend = $lastMonthCost > 0 
            ? round((($thisMonthCost - $lastMonthCost) / $lastMonthCost) * 100, 2) 
            : 0;

        return [
            Stat::make('This Month Cost', '₱' . number_format($thisMonthCost, 2))
                ->description($costTrend >= 0 ? "+{$costTrend}% vs last month" : "{$costTrend}% vs last month")
                ->descriptionIcon($costTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($costTrend >= 0 ? 'warning' : 'success'),

            Stat::make('Last Month Cost', '₱' . number_format($lastMonthCost, 2))
                ->description('Previous month spending')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
