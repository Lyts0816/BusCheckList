<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use App\Models\SystemUnit;
use App\Models\Printer;
use App\Models\Peripherals;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MaintenanceByAssetCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Maintenance by Asset Category';
    protected string $color = 'info';
    protected int|string|array $columnSpan = 4;

    protected function getData(): array
    {
        $systemUnits = AssetMaintenanceLog::where('maintainable_type', SystemUnit::class)->count();
        $printers = AssetMaintenanceLog::where('maintainable_type', Printer::class)->count();
        $peripherals = AssetMaintenanceLog::where('maintainable_type', Peripherals::class)->count();

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
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['System Unit', 'Printer', 'Peripheral'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
