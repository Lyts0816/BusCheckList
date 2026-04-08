<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use App\Models\SystemUnit;
use App\Models\Printer;
use App\Models\Peripherals;
use Filament\Widgets\ChartWidget;

class MaintenanceByAssetCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Maintenance by Asset Category';
    protected string $color = 'info';
    protected int|string|array $columnSpan = 4;

    protected function getData(): array
    {
        $filters = session('asset_maintenance_filters', []);
        $baseQuery = AssetMaintenanceLog::query();

        if (!empty($filters['start_date'])) {
            $baseQuery->whereDate('maintenance_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $baseQuery->whereDate('maintenance_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['maintenance_type'])) {
            $baseQuery->where('maintenance_type', $filters['maintenance_type']);
        }

        $systemUnits = (clone $baseQuery)->where('maintainable_type', SystemUnit::class)->count();
        $printers = (clone $baseQuery)->where('maintainable_type', Printer::class)->count();
        $peripherals = (clone $baseQuery)->where('maintainable_type', Peripherals::class)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Maintenance Count',
                    'data' => [$systemUnits, $printers, $peripherals],
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                    ],
                    'borderColor' => [
                        '#1e40af',
                        '#065f46',
                        '#b45309',
                    ],
                    // 'borderWidth' => 2,
                ],
            ],
            'labels' => ['System Unit', 'Printer', 'Peripheral'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // protected function getOptions(): array
    // {
    //     return [
    //         'maintainAspectRatio' => false,
    //         'responsive' => true,
    //         'plugins' => [
    //             'legend' => [
    //                 'display' => true,
    //             ],
    //         ],
    //     ];
    // }
}
