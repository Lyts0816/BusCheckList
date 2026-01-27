<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use BackedEnum;

class OperationsDashboard extends BaseDashboard
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;
    // ...
    protected static string $routePath = '/operations-dashboard';

    protected static ?string $title = 'Operations Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\LogbookBorrowedStats::class,
            \App\Filament\Widgets\BorrowLogbookDashboard::class,
            \App\Filament\Widgets\LogbookDepartmentWidget::class,

        ];
    }
}
