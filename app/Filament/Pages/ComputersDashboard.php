<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use BackedEnum;

class ComputersDashboard extends BaseDashboard
{
    // ...
    protected static string $routePath = '/computers-dashboard';

    protected static ?string $title = 'Computer Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\SystemUnitProcessorWidget::class,
            \App\Filament\Widgets\SystemUnitRamWidget::class,
            \App\Filament\Widgets\SystemUnitStorageWidget::class,
            \App\Filament\Widgets\SystemUnitWidget::class,
        ];
    }
}
