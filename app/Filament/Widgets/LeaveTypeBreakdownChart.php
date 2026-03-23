<?php

namespace App\Filament\Widgets;

use App\Models\LeaveLog;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeaveTypeBreakdownChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected bool $isCollapsible = true;

    protected ?string $heading = 'Leave Type Breakdown';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $leaveTypeData = $this->getScopedQuery()
            ->selectRaw('leave_type, count(*) as total')
            ->groupBy('leave_type')
            ->orderByDesc('total')
            ->pluck('total', 'leave_type');

        return [
            'labels' => $leaveTypeData->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Leave requests',
                    'data' => $leaveTypeData->values()->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
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
