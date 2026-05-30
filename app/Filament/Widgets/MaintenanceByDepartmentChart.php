<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MaintenanceByDepartmentChart extends ChartWidget
{
    protected ?string $heading = 'Most Replaced Items';
    protected string $color = 'warning';
    protected int|string|array $columnSpan = 4;

    protected function getData(): array
    {
        $filters = session('asset_maintenance_filters', []);
        $query = AssetMaintenanceLog::query();

        if (!empty($filters['start_date'])) {
            $query->whereDate('maintenance_date', '>=', $filters['start_date'], 'and');
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('maintenance_date', '<=', $filters['end_date'], 'and');
        }

        if (!empty($filters['maintenance_type'])) {
            $query->where('maintenance_type', $filters['maintenance_type']);
        }

        $data = $query->leftJoin('office_supplies as os', 'os.id', '=', 'asset_maintenance_logs.office_supply_id')
            ->selectRaw("CASE
                WHEN os.id IS NULL THEN 'N/A'
                WHEN os.brand IS NOT NULL AND os.brand != '' THEN CONCAT(os.name, ' (', os.brand, ')')
                ELSE os.name
            END as replacement_item, COUNT(*) as count")
            ->whereNotNull('asset_maintenance_logs.office_supply_id', 'and')
            ->groupBy('asset_maintenance_logs.office_supply_id', 'os.name', 'os.brand')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'replacement_item');

        $labels = $data->keys()->values();
        $values = $data->values()->map(fn ($count) => (int) $count);

        return [
            'datasets' => [
                [
                    'label' => 'Replacement Count',
                    'data' => $values->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#065f46',
                    // 'borderWidth' => 2,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
}
