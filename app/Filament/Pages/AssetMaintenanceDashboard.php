<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use BackedEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use UnitEnum;

class AssetMaintenanceDashboard extends BaseDashboard
{
    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;

    protected static string $routePath = '/asset-maintenance-dashboard';

    protected static ?string $title = 'Asset Maintenance Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench';

    protected static UnitEnum|string|null $navigationGroup = 'ASSETS';

    public static function canAccess(): bool
    {
        $role = Auth::user()->role;
        return in_array($role, [
            User::ROLE_ADMIN,
            User::ROLE_ADMIN_OPERATIONS,
            User::ROLE_USER_MIS,
            User::ROLE_OPERATIONS,
        ]);
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\MaintenanceStatsWidget::class,
            \App\Filament\Widgets\MaintenanceByTypeChart::class,
            \App\Filament\Widgets\MaintenanceByAssetCategoryChart::class,
            \App\Filament\Widgets\MaintenanceByDepartmentChart::class,
            \App\Filament\Widgets\MaintenanceCostWidget::class,
            \App\Filament\Widgets\MostMaintainedAssetsChart::class,
            \App\Filament\Widgets\RecentMaintenanceLogsWidget::class,
        ];
    }
}
