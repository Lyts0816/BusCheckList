<?php

namespace App\Filament\Widgets;

use App\Models\AssetMaintenanceLog;
use App\Models\SystemUnit;
use App\Models\Printer;
use App\Models\Peripherals;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MostMaintainedAssetsChart extends ChartWidget
{
    protected ?string $heading = 'Most Frequently Maintained Assets';
    protected string $color = 'primary';
    protected int|string|array $columnSpan = 12;
    protected bool $isCollapsible = true;
    protected ?string $maxHeight = '300px';

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

        $data = $query->select('maintainable_type', 'maintainable_id', DB::raw('COUNT(*) as count'))
            ->groupBy('maintainable_type', 'maintainable_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $count = $item->count;
            $values[] = $count;

            if ($item->maintainable_type === SystemUnit::class) {
                $asset = SystemUnit::find($item->maintainable_id);
                $labels[] = $asset ? $asset->asset_code : 'Unknown (SU)';
            } elseif ($item->maintainable_type === Printer::class) {
                $asset = Printer::find($item->maintainable_id);
                $labels[] = $asset ? $asset->asset_code : 'Unknown (Printer)';
            } elseif ($item->maintainable_type === Peripherals::class) {
                $asset = Peripherals::find($item->maintainable_id);
                $labels[] = $asset ? $asset->asset_code : 'Unknown (Peripheral)';
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Maintenance Count',
                    'data' => $values,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#b45309',
                ],
            ],
            'labels' => $labels,
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
