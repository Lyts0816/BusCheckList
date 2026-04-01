<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MaintenanceByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Maintenance by Type';
    protected string $color = 'success';
    protected int|string|array $columnSpan = 4;

    protected function getData(): array
    {
        $data = AssetMaintenanceLog::select('maintenance_type', DB::raw('COUNT(*) as count'))
            ->groupBy('maintenance_type')
            ->pluck('count', 'maintenance_type');

        $labels = $data->keys()->map(fn ($type) => ucfirst(str_replace('_', ' ', $type)))->values();
        $values = $data->values();

        return [
            'datasets' => [
                [
                    'label' => 'Count',
                    'data' => $values->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                    ],
                    'borderColor' => [
                        '#1e40af',
                        '#065f46',
                        '#b45309',
                        '#7f1d1d',
                        '#5b21b6',
                    ],
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
