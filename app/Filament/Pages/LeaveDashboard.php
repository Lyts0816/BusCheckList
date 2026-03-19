<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class LeaveDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected bool $isCollapsible = true;

    protected static bool $isLazy = false;

    protected static string $routePath = '/leave-dashboard';

    protected static ?string $title = 'Leave Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static string|UnitEnum|null $navigationGroup = 'LEAVE MANAGEMENT';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->canViewleaveModule() || $user->hasAdminLeaveModule();
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('department')
                    ->label('Department')
                    ->options(fn (): array => Employee::query()
                        ->whereNotNull('department')
                        ->where('department', '!=', '')
                        ->distinct()
                        ->orderBy('department')
                        ->pluck('department', 'department')
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->visible(fn (): bool => (bool) (Auth::user()?->isAdmin() || Auth::user()?->isAdminLeave())),

                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->maxDate(fn ($get) => $get('end_date') ?: now())
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->minDate(fn ($get) => $get('start_date'))
                    ->maxDate(now())
                    ->native(false),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\LeaveOverviewStats::class,
            \App\Filament\Widgets\LeaveTypeBreakdownChart::class,
        ];
    }
}
