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
        $data = AssetMaintenanceLog::select('performed_by', DB::raw('COUNT(*) as count'))
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
                    'borderWidth' => 2,
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
