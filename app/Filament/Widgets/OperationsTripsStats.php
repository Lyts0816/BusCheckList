<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OperationsTripsStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $tripsToday = DispatchedTrips::whereDate('date_time_of_departure', $today)->count();
        $arrivedToday = DispatchedTrips::whereDate('date_time_of_arrival', $today)->count();
        $inTransit = DispatchedTrips::whereNotNull('date_time_of_departure')
            ->whereNull('date_time_of_arrival')
            ->count();

        return [
            Stat::make('Trips Today', $tripsToday)
                ->description('Departed today')
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),
            Stat::make('In Transit', $inTransit)
                ->description('No arrival yet')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('warning'),
            Stat::make('Arrived Today', $arrivedToday)
                ->description('Arrivals today')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}
