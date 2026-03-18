<?php

namespace App\Filament\Widgets;

use App\Models\LeaveLog;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeaveOverviewStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $query = $this->getScopedQuery();

        $totalRequests = (clone $query)->count();

        $thisMonthRequests = (clone $query)
            ->whereBetween('date_filed', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $activeLeaves = (clone $query)
            ->whereDate('from_date', '<=', today())
            ->whereDate('to_date', '>=', today())
            ->count();

        $upcomingLeaves = (clone $query)
            ->whereDate('from_date', '>', today())
            ->count();

        return [
            Stat::make('Total Leave Requests', $totalRequests)
                ->description('All records in your scope')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Requests This Month', $thisMonthRequests)
                ->description('Filed in current month')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Currently On Leave', $activeLeaves)
                ->description('Active today')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Upcoming Leaves', $upcomingLeaves)
                ->description('Start date is in the future')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
        ];
    }

    private function applyDashboardFilters(Builder $query): Builder
    {
        $department = $this->pageFilters['department'] ?? null;
        $startDate = $this->pageFilters['start_date'] ?? null;
        $endDate = $this->pageFilters['end_date'] ?? null;

        if (! empty($department)) {
            $query->whereHas('employee', function (Builder $employeeQuery) use ($department): void {
                $employeeQuery->where('department', $department);
            });
        }

        if (! empty($startDate)) {
            $query->whereDate('date_filed', '>=', $startDate);
        }

        if (! empty($endDate)) {
            $query->whereDate('date_filed', '<=', $endDate);
        }

        return $query;
    }

    private function getScopedQuery(): Builder
    {
        $query = LeaveLog::query();
        $user = Auth::user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin() || $user->isAdminLeave()) {
            return $this->applyDashboardFilters($query);
        }

        $allowedDepartments = $user->departmentRoleAliases();

        if (empty($allowedDepartments)) {
            return $query->whereRaw('1 = 0');
        }

        $query = $query->whereHas('employee', function (Builder $employeeQuery) use ($allowedDepartments): void {
            $employeeQuery->where(function (Builder $departmentQuery) use ($allowedDepartments): void {
                $departmentQuery
                    ->whereIn('department', $allowedDepartments)
                    ->orWhereNull('department');
            });
        });

        return $this->applyDashboardFilters($query);
    }
}
