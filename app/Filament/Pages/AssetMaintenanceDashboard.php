<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AssetMaintenanceLogs\AssetMaintenanceLogResource;
use App\Filament\Resources\AssetMaintenanceLogs\Tables\AssetMaintenanceLogsTable;
use App\Models\AssetMaintenanceLog;
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

class AssetMaintenanceDashboard extends BaseDashboard implements HasTable
{
    use HasFiltersForm, HasTabs, InteractsWithTable {
        HasFiltersForm::normalizeTableFilterValuesFromQueryString insteadof InteractsWithTable;
        InteractsWithTable::normalizeTableFilterValuesFromQueryString as normalizeTableFilterValuesFromQueryStringForTable;
    }

    #[Url(as: 'tab')]
    public ?string $activeTab = null;

    protected bool $isCollapsible = true;

    protected static bool $isLazy = false;

    protected static string $routePath = '/asset-maintenance-dashboard';

    protected static ?string $title = 'Asset Maintenance Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'MAINTENANCE LOGS';

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

    public function mount(): void
    {
        $this->loadDefaultActiveTab();
        // Clear filters on initial load
        if (!request()->has('filters')) {
            $this->filters = [];
        }
        // Store filters in session for widgets to access
        session(['asset_maintenance_filters' => $this->filters]);
    }

    public function updatedFilters(): void
    {
        // Update session whenever filters change
        session(['asset_maintenance_filters' => $this->filters]);
        // Dispatch refresh event to all widgets
        $this->dispatch('$refresh');
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->maxDate(fn ($get) => $get('end_date') ?: now())
                    ->native(true),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->minDate(fn ($get) => $get('start_date'))
                    ->maxDate(now())
                    ->native(true),

                Select::make('maintenance_type')
                    ->label('Maintenance Type')
                    ->options(fn (): array => AssetMaintenanceLog::query()
                        ->whereNotNull('maintenance_type')
                        ->where('maintenance_type', '!=', '')
                        ->distinct()
                        ->orderBy('maintenance_type')
                        ->pluck('maintenance_type', 'maintenance_type')
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->default(null),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\MaintenanceStatsWidget::class,
            \App\Filament\Widgets\MaintenanceByTypeChart::class,
            \App\Filament\Widgets\MaintenanceByAssetCategoryChart::class,
            \App\Filament\Widgets\MaintenanceByDepartmentChart::class,
            \App\Filament\Widgets\MostMaintainedAssetsChart::class,
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
        return AssetMaintenanceLogsTable::configure($table)
            ->query(fn (): Builder => $this->getFilteredBaseQuery())
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...));
    }

    public function getTabs(): array
    {
        // Use unfiltered query for tabs so they always show all maintenance types
        $unfilteredQuery = AssetMaintenanceLogResource::getEloquentQuery();

        $tabs = [
            'all' => Tab::make('All')
                ->badge(fn (): int => (clone $this->getFilteredBaseQuery())->count()),
        ];

        $maintenanceTypes = (clone $unfilteredQuery)
            ->whereNotNull('maintenance_type')
            ->select('maintenance_type')
            ->distinct()
            ->orderBy('maintenance_type')
            ->pluck('maintenance_type')
            ->filter();

        foreach ($maintenanceTypes as $maintenanceType) {
            $tabs[(string) $maintenanceType] = Tab::make((string) $maintenanceType)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('maintenance_type', $maintenanceType))
                ->badge(fn (): int => (clone $this->getFilteredBaseQuery())->where('maintenance_type', $maintenanceType)->count());
        }

        return $tabs;
    }

    protected function getFilteredBaseQuery(): Builder
    {
        $query = AssetMaintenanceLogResource::getEloquentQuery();

        if (! $query instanceof Builder) {
            return AssetMaintenanceLog::query()->whereRaw('1 = 0');
        }

        $maintenanceType = $this->filters['maintenance_type'] ?? null;
        $startDate = $this->filters['start_date'] ?? null;
        $endDate = $this->filters['end_date'] ?? null;

        if (! empty($maintenanceType)) {
            $query->where('maintenance_type', $maintenanceType);
        }

        if (! empty($startDate)) {
            $query->whereDate('maintenance_date', '>=', $startDate);
        }

        if (! empty($endDate)) {
            $query->whereDate('maintenance_date', '<=', $endDate);
        }

        return $query;
    }
}
