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

        $tripsDeparted = DispatchedTrips::whereHas('dispatchSheet', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('dispatch_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        })
            ->whereNotNull('time_of_departure')
            ->count();

        $tripsArrived = DispatchedTrips::whereHas('dispatchSheet', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('dispatch_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        })
            ->whereNotNull('time_of_arrival')
            ->count();

        $inTransit = DispatchedTrips::whereHas('dispatchSheet', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('dispatch_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        })
            ->whereNotNull('time_of_departure')
            ->whereNull('time_of_arrival')
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
