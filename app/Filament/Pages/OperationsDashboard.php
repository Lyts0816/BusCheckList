<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use BackedEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OperationsDashboard extends BaseDashboard
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;
    // ...
    protected static string $routePath = '/operations-dashboard';

    protected static ?string $title = 'Operations Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    public static function canAccess(): bool
    {
        return  Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_OPERATIONS || Auth::user()->role === User::ROLE_ADMIN_OPERATIONS;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\OperationsChecklistStats::class,
            \App\Filament\Widgets\OperationsTripsStats::class,
            \App\Filament\Widgets\BusStatusWidget::class,
            \App\Filament\Widgets\RoutePopularityWidget::class,
            \App\Filament\Widgets\TripTypeWidget::class,
        ];
    }
}
