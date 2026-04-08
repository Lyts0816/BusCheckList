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
        $filters = session('asset_maintenance_filters', []);
        $query = AssetMaintenanceLog::query();

        if (!empty($filters['start_date'])) {
            $query->whereDate('maintenance_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('maintenance_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['maintenance_type'])) {
            $query->where('maintenance_type', $filters['maintenance_type']);
        }

        $data = $query->select('maintenance_type', DB::raw('COUNT(*) as count'))
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

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
