<?php

namespace App\Filament\Widgets;

use App\Models\BusDailyChecklist;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OperationsChecklistStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $totalToday = BusDailyChecklist::whereDate('check_date', $today)->count();
        $checkedToday = BusDailyChecklist::whereDate('check_date', $today)
            ->where('checked', true)
            ->count();
        $uncheckedToday = BusDailyChecklist::whereDate('check_date', $today)
            ->where('checked', false)
            ->count();

        return [
            Stat::make('Checklists Today', $totalToday)
                ->description('All checklists for today')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('info'),
            Stat::make('Checked Today', $checkedToday)
                ->description('Completed checklists')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Unchecked Today', $uncheckedToday)
                ->description('Pending checklists')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('warning'),
        ];
    }
}
