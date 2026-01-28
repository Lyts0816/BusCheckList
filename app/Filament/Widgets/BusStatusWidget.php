<?php

namespace App\Filament\Widgets;

use App\Models\Bus;
use Filament\Widgets\ChartWidget;

class BusStatusWidget extends ChartWidget
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'Bus Status Overview';

    protected function getData(): array
    {
        $statusCounts = Bus::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->orderBy('status')
            ->pluck('count', 'status');

        if ($statusCounts->isEmpty()) {
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

        $labels = $statusCounts->keys()->toArray();
        $data = $statusCounts->values()->toArray();

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
