<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class OperationsTripsStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['start_date'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['end_date'] ?? now();

        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        $tripsDeparted = DispatchedTrips::whereBetween('date_time_of_departure', [$startDateTime, $endDateTime])->count();
        $tripsArrived = DispatchedTrips::whereBetween('date_time_of_arrival', [$startDateTime, $endDateTime])->count();
        $inTransit = DispatchedTrips::whereNotNull('date_time_of_departure')
            ->whereNull('date_time_of_arrival')
            ->count();

        return [
            Stat::make('Trips Departed', $tripsDeparted)
                ->description('Departed in selected period')
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),
            Stat::make('In Transit', $inTransit)
                ->description('Currently in transit')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('warning'),
            Stat::make('Trips Arrived', $tripsArrived)
                ->description('Arrived in selected period')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}
