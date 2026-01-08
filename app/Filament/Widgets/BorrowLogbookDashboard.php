<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\BorrowItems;

class BorrowLogbookDashboard extends ChartWidget
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected ?string $heading = 'Borrow Logbook Statistics';

    protected function getData(): array
    {
        $borrowedCount = BorrowItems::whereNull('return_date')->count();
        $returnedCount = BorrowItems::whereNotNull('return_date')->count();

        return [
            'labels' => [
                'Borrowed',
                'Returned',
            ],
            'datasets' => [
                [
                    'label' => 'Borrowed',
                    'data' => [
                        $borrowedCount,
                        $returnedCount,
                    ],
                    'backgroundColor' => [
                        '#0ea5e9',
                        '#22c55e',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
