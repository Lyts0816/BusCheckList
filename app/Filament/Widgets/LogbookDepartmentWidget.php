<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\BorrowLogs;

class LogbookDepartmentWidget extends ChartWidget
{
    protected ?string $heading = 'Departments Borrowed';

    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $departmentData = BorrowLogs::with('items')
            ->whereHas('items', function ($query) {
                $query->where('status', 'Borrowed');
            })
            ->selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->pluck('count', 'department');

        return [
            'labels' => $departmentData->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Current borrowed logs by Department',
                    'data' => $departmentData->values()->toArray(),
                    'backgroundColor' => [
                        '#0ea5e9', '#22c55e', '#a855f7', '#f97316', '#ef4444', '#6366f1',
                        '#06b6d4', '#84cc16', '#eab308', '#8b5cf6', '#14b8a6', '#f59e0b',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
