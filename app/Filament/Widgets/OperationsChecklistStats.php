<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use App\Models\Drivers;
use App\Models\Conductors;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class OperationsChecklistStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['start_date'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['end_date'] ?? now();

        $tripsQuery = DispatchedTrips::whereBetween('date_time_of_departure', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);

        $trips = $tripsQuery->count();
        $driversOnDuty = Drivers::where('status', 'Active')->count();
        $conductorsOnDuty = Conductors::where('status', 'Active')->count();

        return [
            Stat::make('Total Trips', $trips)
                ->description('Dispatched trips in selected period')
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),
            Stat::make('Active Drivers', $driversOnDuty)
                ->description('Currently on duty')
                ->descriptionIcon('heroicon-o-user')
                ->color('success'),
            Stat::make('Active Conductors', $conductorsOnDuty)
                ->description('Currently on duty')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning'),
        ];
    }
}

