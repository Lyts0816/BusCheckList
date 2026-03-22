<?php

namespace App\Filament\Pages;

use App\Filament\Resources\LeaveLogs\LeaveLogResource;
use App\Filament\Resources\LeaveLogs\Tables\LeaveLogsTable;
use App\Models\Employee;
use App\Models\LeaveLog;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use UnitEnum;

class LeaveDashboard extends BaseDashboard implements HasTable
{
    use HasFiltersForm, HasTabs, InteractsWithTable {
        HasFiltersForm::normalizeTableFilterValuesFromQueryString insteadof InteractsWithTable;
        InteractsWithTable::normalizeTableFilterValuesFromQueryString as normalizeTableFilterValuesFromQueryStringForTable;
    }

    #[Url(as: 'tab')]
    public ?string $activeTab = null;

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

    public function mount(): void
    {
        $this->loadDefaultActiveTab();
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
                    ->native(true),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->minDate(fn ($get) => $get('start_date'))
                    ->maxDate(now())
                    ->native(true),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\LeaveOverviewStats::class,
            \App\Filament\Widgets\LeaveTypeBreakdownChart::class,
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...(method_exists($this, 'getFiltersForm') ? [$this->getFiltersFormContentComponent()] : []),
                $this->getWidgetsContentComponent(),
                $this->getTabsContentComponent(),
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        return LeaveLogsTable::configure($table)
            ->query(fn (): Builder => $this->getFilteredBaseQuery())
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...));
    }

    public function getTabs(): array
    {
        $baseQuery = $this->getFilteredBaseQuery();

        $tabs = [
            'all' => Tab::make('All')
                ->badge(fn (): int => (clone $baseQuery)->count()),
        ];

        $leaveTypes = (clone $baseQuery)
            ->whereNotNull('leave_type')
            ->select('leave_type')
            ->distinct()
            ->orderBy('leave_type')
            ->pluck('leave_type')
            ->filter();

        foreach ($leaveTypes as $leaveType) {
            $tabs[(string) $leaveType] = Tab::make((string) $leaveType)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('leave_type', $leaveType))
                ->badge(fn (): int => (clone $baseQuery)->where('leave_type', $leaveType)->count());
        }

        return $tabs;
    }

    protected function getFilteredBaseQuery(): Builder
    {
        $query = LeaveLogResource::getEloquentQuery();

        if (! $query instanceof Builder) {
            return LeaveLog::query()->whereRaw('1 = 0');
        }

        $department = $this->filters['department'] ?? null;
        $startDate = $this->filters['start_date'] ?? null;
        $endDate = $this->filters['end_date'] ?? null;

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
}
