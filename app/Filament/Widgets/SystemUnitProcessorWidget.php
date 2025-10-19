<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SystemUnit;

class SystemUnitProcessorWidget extends ChartWidget
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;
    
    protected ?string $heading = 'System Units by Processor';

    protected function getData(): array
    {
        $systemUnitsI3 = SystemUnit::where('processor', 'Intel Core i3')->count();
        $systemUnitsI5 = SystemUnit::where('processor', 'Intel Core i5')->count();
        $systemUnitsI7 = SystemUnit::where('processor', 'Intel Core i7')->count();
        $systemUnitsI9 = SystemUnit::where('processor', 'Intel Core i9')->count();
        $systemUnitsPentium = SystemUnit::where('processor', 'Intel Pentium')->count();
        $systemUnitsCeleron = SystemUnit::where('processor', 'Intel Celeron')->count();
        $systemUnitsNoProcessor = SystemUnit::where('processor', 'Cant find Processor')->count();

        return [
            'labels' => ['Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'Intel Pentium', 'Intel Celeron', 'Cant find Processor'],
            'datasets' => [
                [
                    'data' => [$systemUnitsI3, $systemUnitsI5, $systemUnitsI7, $systemUnitsI9, $systemUnitsPentium, $systemUnitsCeleron, $systemUnitsNoProcessor],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#6C757D'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
