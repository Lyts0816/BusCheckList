<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use App\Models\Drivers;
use App\Models\Conductors;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OperationsChecklistStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $tripsToday = DispatchedTrips::whereDate('date_time_of_departure', $today)->count();
        $driversOnDuty = Drivers::where('status', 'Active')->count();
        $conductorsOnDuty = Conductors::where('status', 'Active')->count();

        return [
            Stat::make('Trips Today', $tripsToday)
                ->description('Dispatched trips')
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),
            Stat::make('Active Drivers', $driversOnDuty)
                ->description('On duty')
                ->descriptionIcon('heroicon-o-user')
                ->color('success'),
            Stat::make('Active Conductors', $conductorsOnDuty)
                ->description('On duty')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning'),
        ];
    }
}

