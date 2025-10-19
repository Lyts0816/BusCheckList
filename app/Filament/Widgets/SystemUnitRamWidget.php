<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SystemUnit;

class SystemUnitRamWidget extends ChartWidget
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'System Units by RAM';

    protected function getData(): array
    {
        // Aggregate distinct RAM values dynamically
        $rows = SystemUnit::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(ram), ''), 'Unknown') as ram_label, COUNT(*) as total")
            ->groupBy('ram_label')
            ->orderByDesc('total')
            ->get();
        
        // Optional: keep top 8, group the rest as "Other"
        $top = $rows->take(8);
        $othersTotal = $rows->skip(8)->sum('total');
        if ($othersTotal > 0) {
            $top = $top->push((object) ['ram_label' => 'Other', 'total' => $othersTotal]);
        }

        $labels = $top->pluck('ram_label')->all();
        $data = $top->pluck('total')->all();

        $palette = ['#36A2EB','#FF6384','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#6C757D','#2ECC71','#E67E22','#9B59B6'];
        $colors = array_map(fn ($i) => $palette[$i % count($palette)], range(0, max(count($labels) - 1, 0)));

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
