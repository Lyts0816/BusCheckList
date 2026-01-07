<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\BorrowLogbook;

class LogbookBorrowedStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Borrowed', BorrowLogbook::where('status', 'Borrowed')->count())
                ->description('Borrowed status in the logbooks')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('danger'),
            Stat::make('Total Returned', BorrowLogbook::where('status', 'Returned')->count())
                ->description('Returned status in the logbooks')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Total Records', BorrowLogbook::count())
                ->description('All records in the logbooks')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),
        ];
    }
}
