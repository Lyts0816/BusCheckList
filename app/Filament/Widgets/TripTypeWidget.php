<?php

namespace App\Filament\Widgets;

use App\Models\DispatchedTrips;
use App\Models\NatureOfTrip;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class TripTypeWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'Trips by Type';

    protected function getData(): array
    {
        $startDate = $this->pageFilters['start_date'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['end_date'] ?? now();

        $tripTypeData = DispatchedTrips::with('natureOfTrip')
            ->whereBetween('date_time_of_departure', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->selectRaw('nature_of_trip_id, count(*) as count')
            ->groupBy('nature_of_trip_id')
            ->get()
            ->mapWithKeys(function ($item) {
                $typeName = $item->natureOfTrip ? $item->natureOfTrip->nature_of_trip_name : 'Unknown';
                return [$typeName => $item->count];
            })
            ->sortDesc();

        if ($tripTypeData->isEmpty()) {
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
            '#06b6d4', '#f59e0b', '#22c55e', '#ef4444', '#0ea5e9',
            '#a855f7', '#14b8a6', '#6366f1', '#ec4899', '#84cc16',
        ];

        $labels = $tripTypeData->keys()->toArray();
        $data = $tripTypeData->values()->toArray();
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
