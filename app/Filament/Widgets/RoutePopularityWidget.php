<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use App\Models\Routes;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class RoutePopularityWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'Top Routes by Trips';

    protected function getData(): array
    {
        $startDate = $this->pageFilters['start_date'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['end_date'] ?? now();

        $routeData = DispatchedTrips::with('route')
            ->whereBetween('date_time_of_departure', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->selectRaw('route_id, count(*) as count')
            ->groupBy('route_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->mapWithKeys(function ($item) {
                $routeName = $item->route ? $item->route->from . ' → ' . $item->route->to : 'Unknown';
                return [$routeName => $item->count];
            });

        if ($routeData->isEmpty()) {
            return [
                'labels' => ['No Data'],
                'datasets' => [
                    [
                        'data' => [0],
                        'backgroundColor' => ['#94a3b8'],
                    ],
                ],
            ];
        }

        $colors = [
            '#0ea5e9', '#22c55e', '#f59e0b', '#ef4444', '#a855f7',
            '#14b8a6', '#6366f1', '#f97316', '#84cc16', '#ec4899',
        ];

        $labels = $routeData->keys()->toArray();
        $data = $routeData->values()->toArray();
        $backgroundColor = array_slice($colors, 0, count($data));

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
