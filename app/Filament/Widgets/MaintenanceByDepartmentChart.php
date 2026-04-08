<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MaintenanceByDepartmentChart extends ChartWidget
{
    protected ?string $heading = 'Maintenance by Performer';
    protected string $color = 'warning';
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

        $data = $query->select('performed_by', DB::raw('COUNT(*) as count'))
            ->whereNotNull('performed_by')
            ->groupBy('performed_by')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'performed_by');

        $labels = $data->keys()->values();
        $values = $data->values();

        return [
            'datasets' => [
                [
                    'label' => 'Maintenance Count',
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
}
