<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use BackedEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use UnitEnum;

class LogbookDashboard extends BaseDashboard
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;
    // ...
    protected static string $routePath = '/logbook-dashboard';

    protected static ?string $title = 'Logbook Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'LOGBOOKS';
    

    public static function canAccess(): bool
    {
        return  Auth::user()->role === User::ROLE_ADMIN;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\LogbookBorrowedStats::class,
            \App\Filament\Widgets\BorrowLogbookDashboard::class,
            \App\Filament\Widgets\LogbookDepartmentWidget::class,

        ];
    }
}
