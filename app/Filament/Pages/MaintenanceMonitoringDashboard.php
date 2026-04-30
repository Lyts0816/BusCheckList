<?php

namespace App\Filament\Pages;

use App\Models\AssetMaintenanceLog;
use App\Models\AssignedComputer;
use App\Models\Departments;
use App\Models\Peripherals;
use App\Models\Printer;
use App\Models\SystemUnit;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use UnitEnum;

class MaintenanceMonitoringDashboard extends BaseDashboard implements HasTable
{
    use HasTabs, InteractsWithTable;

    #[Url(as: 'tab')]
    public ?string $activeTab = null;

    protected static bool $isLazy = false;

    protected static string $routePath = '/maintenance-monitoring-dashboard';

    protected static ?string $title = 'Maintenance Monitoring Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'COMPUTER & PERIPHERALS';

    public static function canAccess(): bool
    {
        return Auth::user()->role === User::ROLE_ADMIN;
    }

    public function mount(): void
    {
        $this->activeTab ??= 'ALL';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getTabsContentComponent(),
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getBaseQuery())
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...))
            ->columns([
                TextColumn::make('display_id')
                    ->label('ID')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('asset_maintenance_logs.id', $direction);
                    }),

                TextColumn::make('assigned_to')
                    ->label('Assigned To'),

                TextColumn::make('department')
                    ->label('Department'),

                TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->searchable(),

                TextColumn::make('component_name')
                    ->label('Component')
                    ->formatStateUsing(fn ($state): string => $state ?: 'N/A')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('maintenance_type')
                    ->label('Maintenance Type')
                    ->formatStateUsing(fn ($state): string => $state ?: 'No maintenance yet')
                    ->sortable()
                    ->badge(),

                TextColumn::make('recent_maintenance_date')
                    ->label('Recent Maintenance Date')
                    ->getStateUsing(function (AssetMaintenanceLog $record): string {
                        if (! $record->recent_maintenance_date) {
                            return 'No maintenance yet';
                        }

                        return Carbon::parse($record->recent_maintenance_date)->format('M d, Y');
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderByRaw('CASE WHEN logs.maintenance_date IS NULL THEN 1 ELSE 0 END')
                            ->orderBy('logs.maintenance_date', $direction);
                    }),

                TextColumn::make('days_since_maintenance')
                    ->label('Days Since Maintenance')
                    ->getStateUsing(function (AssetMaintenanceLog $record): string {
                        if (! $record->recent_maintenance_date) {
                            return 'No maintenance yet';
                        }

                        $maintenanceDate = Carbon::parse($record->recent_maintenance_date)->startOfDay();
                        $today = now()->startOfDay();

                        if ($maintenanceDate->greaterThan($today)) {
                            return 'Scheduled in ' . $today->diffInDays($maintenanceDate) . ' days';
                        }

                        $totalDays = $maintenanceDate->diffInDays($today);
                        $parts = $maintenanceDate->diff($today);
                        $segments = [];

                        if ($parts->m > 0) {
                            $segments[] = $parts->m . ' month' . ($parts->m > 1 ? 's' : '');
                        }

                        if ($parts->d > 0 || empty($segments)) {
                            $segments[] = $parts->d . ' day' . ($parts->d > 1 ? 's' : '');
                        }

                        return $totalDays . ' days (' . implode(', ', $segments) . ')';
                    })
                    ->badge()
                    ->color(function (AssetMaintenanceLog $record): string {
                        if (! $record->recent_maintenance_date) {
                            return 'gray';
                        }

                        $days = Carbon::parse($record->recent_maintenance_date)
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay(), false);

                        if ($days >= 365) {
                            return 'danger';
                        }

                        if ($days >= 180) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderByRaw('CASE WHEN logs.maintenance_date IS NULL THEN 1 ELSE 0 END')
                            ->orderBy('logs.maintenance_date', $direction);
                    }),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->label('Department')
                    ->options(function (): array {
                        // Get departments that have assigned computers
                        $assignedComputerDepartmentIds = AssignedComputer::query()
                            ->whereNotNull('department_id', 'and')
                            ->distinct()
                            ->pluck('department_id')
                            ->toArray();

                        $departmentOptions = Departments::query()
                            ->whereIn('id', $assignedComputerDepartmentIds, 'and', false)
                            ->pluck('name', 'id')
                            ->toArray();

                        // Add printer departments
                        $printerDepartments = Printer::query()
                            ->whereNotNull('department', 'and')
                            ->where('department', '!=', '')
                            ->distinct()
                            ->pluck('department')
                            ->toArray();

                        $allDepartments = array_values(array_unique(array_merge($departmentOptions, $printerDepartments)));
                        sort($allDepartments);

                        return array_combine($allDepartments, $allDepartments);
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $department = $data['value'] ?? null;

                        if (! $department) {
                            return $query;
                        }

                        // Check if it's a department ID or name
                        $deptId = Departments::query()->where('name', $department)->value('id');
                        if ($deptId) {
                            return $query->where('asset_maintenance_logs.department', $deptId);
                        }

                        return $query->where('asset_maintenance_logs.department', $department);
                    }),
            ])
            ->headerActions([
                Action::make('export_csv')
                    ->label('Export all records')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $currentUrl = request()->fullUrl();
                        $parsedUrl = parse_url($currentUrl);

                        $queryParams = [];
                        if (isset($parsedUrl['query'])) {
                            parse_str($parsedUrl['query'], $queryParams);
                        }

                        $exportUrl = url('/export/maintenance-monitoring-dashboard');
                        $exportParams = [];

                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        if (isset($queryParams['tab'])) {
                            $exportParams['tab'] = $queryParams['tab'];
                        }

                        if (! empty($exportParams)) {
                            $exportUrl .= '?' . http_build_query($exportParams);
                        }

                        return redirect($exportUrl);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = url('/export/maintenance-monitoring-dashboard') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ])
            ->defaultSort('maintenance_type', 'desc');
    }

    public function getTabs(): array
    {
        $baseQuery = $this->getBaseQuery();

        return [
            'ALL' => Tab::make()
                ->label('ALL')
                ->badge(fn (): int => (clone $baseQuery)->count()),

            'SYSTEM UNITS' => Tab::make()
                ->badge(fn (): int => (clone $baseQuery)->where('asset_maintenance_logs.maintainable_type', SystemUnit::class)->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('asset_maintenance_logs.maintainable_type', SystemUnit::class)),

            'PRINTERS' => Tab::make()
                ->badge(fn (): int => (clone $baseQuery)->where('asset_maintenance_logs.maintainable_type', Printer::class)->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('asset_maintenance_logs.maintainable_type', Printer::class)),

            'UPS' => Tab::make()
                ->badge(fn (): int => (clone $baseQuery)
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'UPS')
                    ->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'UPS')),

            'MONITOR' => Tab::make()
                ->badge(fn (): int => (clone $baseQuery)
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'MONITOR')
                    ->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'MONITOR')),

            'KEYBOARD' => Tab::make()
                ->badge(fn (): int => (clone $baseQuery)
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'KEYBOARD')
                    ->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'KEYBOARD')),

            'MOUSE' => Tab::make()
                ->badge(fn (): int => (clone $baseQuery)
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'MOUSE')
                    ->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('asset_maintenance_logs.maintainable_type', Peripherals::class)
                    ->where('asset_maintenance_logs.item_type', 'MOUSE')),
        ];
    }

    protected function getBaseQuery(): Builder
    {
        $systemUnitsQuery = DB::table('system_units as su')
            ->selectRaw(
                "(100000000 + su.id) as id,
                CONCAT('SU-', su.id) as display_id,
                ? as maintainable_type,
                su.id as maintainable_id,
                null as item_type,
                COALESCE(su.serial_number, su.asset_code, 'N/A') as serial_number,
                COALESCE((select ac.assigned_to from assigned_computers ac where ac.system_unit_id = su.id limit 1), 'Unassigned') as assigned_to,
                COALESCE((select ac.department from assigned_computers ac where ac.system_unit_id = su.id limit 1), 'N/A') as department",
                [SystemUnit::class],
            );

        $printersQuery = DB::table('printers as pr')
            ->selectRaw(
                "(200000000 + pr.id) as id,
                CONCAT('PR-', pr.id) as display_id,
                ? as maintainable_type,
                pr.id as maintainable_id,
                null as item_type,
                COALESCE(pr.printer_serial_number, pr.asset_code, 'N/A') as serial_number,
                'Unassigned' as assigned_to,
                COALESCE(pr.department, 'N/A') as department",
                [Printer::class],
            );

        $peripheralsQuery = DB::table('peripherals as p')
            ->selectRaw(
                "(300000000 + p.id) as id,
                CONCAT('PE-', p.id) as display_id,
                ? as maintainable_type,
                p.id as maintainable_id,
                UPPER(p.item_type) as item_type,
                COALESCE(p.serial_number, p.asset_code, 'N/A') as serial_number,
                COALESCE((
                    select ac.assigned_to
                    from assigned_computers ac
                    where ac.keyboard_id = p.id
                        or ac.mouse_id = p.id
                        or ac.monitor_id = p.id
                        or ac.ups_id = p.id
                    limit 1
                ), 'Unassigned') as assigned_to,
                COALESCE((
                    select ac.department
                    from assigned_computers ac
                    where ac.keyboard_id = p.id
                        or ac.mouse_id = p.id
                        or ac.monitor_id = p.id
                        or ac.ups_id = p.id
                    limit 1
                ), 'N/A') as department",
                [Peripherals::class],
            );

        $assetsQuery = $systemUnitsQuery
            ->unionAll($printersQuery)
            ->unionAll($peripheralsQuery);

        $latestLogSubquery = DB::table('asset_maintenance_logs as aml')
            ->selectRaw('aml.maintainable_type, aml.maintainable_id, MAX(aml.id) as latest_log_id')
            ->groupBy('aml.maintainable_type', 'aml.maintainable_id');

        return AssetMaintenanceLog::query()
            ->fromSub($assetsQuery, 'asset_maintenance_logs')
            ->leftJoinSub($latestLogSubquery, 'latest', 'and', false, function ($join): void {
                $join->on('asset_maintenance_logs.maintainable_type', '=', 'latest.maintainable_type')
                    ->on('asset_maintenance_logs.maintainable_id', '=', 'latest.maintainable_id');
            })
            ->leftJoin('asset_maintenance_logs as logs', 'logs.id', '=', 'latest.latest_log_id', 'and', false)
            ->leftJoin('components as c', 'c.id', '=', 'logs.component_id', 'and', false)
            ->selectRaw('asset_maintenance_logs.id, asset_maintenance_logs.display_id, asset_maintenance_logs.maintainable_type, asset_maintenance_logs.maintainable_id, asset_maintenance_logs.item_type, asset_maintenance_logs.serial_number, asset_maintenance_logs.assigned_to, asset_maintenance_logs.department, logs.maintenance_type, logs.maintenance_date as recent_maintenance_date, c.name as component_name');
    }
}
