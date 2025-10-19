<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SystemUnit;

class SystemUnitWidget extends ChartWidget
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'System Units by Operating System';

    protected function getData(): array
    {
        $systemUnitsW10 = SystemUnit::where('OS', 'Windows 10 Pro')->count();
        $systemUnitsW11 = SystemUnit::where('OS', 'Windows 11 Pro')->count();
        $systemUnitsW7 = SystemUnit::where('OS', 'Windows 7 Pro')->count();
        $systemUnitsW8 = SystemUnit::where('OS', 'Windows 8 Pro')->count();
        $systemUnitNoOS = SystemUnit::whereIn('OS', ['Other', 'Cant find OS'])->count();

        return [
            'labels' => ['WINDOWS 10', 'WINDOWS 11', 'WINDOWS 8', 'WINDOWS 7', 'CANT FIND OS'],
            'datasets' => [
                [
                    'data' => [$systemUnitsW10, $systemUnitsW11, $systemUnitsW8, $systemUnitsW7, $systemUnitNoOS],
                    'backgroundColor' => ['#007BFF', '#FFC107', '#28A745', '#DC3545', '#6C757D'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
