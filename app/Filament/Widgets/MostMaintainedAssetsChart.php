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

    protected function getData(): array
    {
        $data = AssetMaintenanceLog::select('maintainable_type', 'maintainable_id', DB::raw('COUNT(*) as count'))
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
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
