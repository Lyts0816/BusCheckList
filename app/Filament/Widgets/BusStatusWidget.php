<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use App\Models\BusClass;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class BusStatusWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'Trips by Bus Class';

    protected function getData(): array
    {
        $startDate = $this->pageFilters['start_date'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['end_date'] ?? now();

        $busClassData = DispatchedTrips::with('busClass')
            ->whereBetween('date_time_of_departure', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->selectRaw('bus_class_id, count(*) as count')
            ->groupBy('bus_class_id')
            ->get()
            ->mapWithKeys(function ($item) {
                $className = $item->busClass ? $item->busClass->class_name : 'Unknown';
                return [$className => $item->count];
            })
            ->sortDesc();

        if ($busClassData->isEmpty()) {
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
            '#22c55e', '#f59e0b', '#ef4444', '#0ea5e9', '#a855f7',
            '#14b8a6', '#6366f1', '#f97316', '#84cc16', '#64748b',
        ];

        $labels = $busClassData->keys()->toArray();
        $data = $busClassData->values()->toArray();
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
        return 'pie';
    }
}

