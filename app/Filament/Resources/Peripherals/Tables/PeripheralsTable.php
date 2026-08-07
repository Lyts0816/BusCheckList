<?php

namespace App\Filament\Resources\Peripherals\Tables;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use App\Models\AssignedComputer;
use App\Models\Departments;
use App\Models\OfficeSupplies;
use App\Models\Peripherals;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;
use Filament\Support\Enums\Size;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;

use Filament\Tables\Enums\RecordActionsPosition;


class PeripheralsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('maintenance_logs_count')
                    ->wrapHeader()
                    ->toggleable()
                    ->sortable()
                    ->alignCenter()
                    ->grow(false)
                    ->label('Maintenance logs')
                    ->badge()
                    ->counts('maintenanceLogs')
                    ->colors(['primary']),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'Good Condition' => 'success',
                            'In Maintenance' => 'warning',
                            'For Repair' => 'info',
                            'Damaged' => 'danger',
                            'Lost' => 'gray',
                            'Retire' => 'danger',
                            'Spare' => 'primary', // Blue
                            default => 'gray',
                        };
                    })
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('item_type')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('assignedComputers.assigned_to')
                    ->wrapHeader()
                    ->label('Assigned To')
                    ->toggleable()
                    // ->searchable()
                    ->getStateUsing(function (Peripherals $record) {
                        $assignedComputers = $record->assignedComputers;

                        // Only return if there are assigned computers
                        if ($assignedComputers && $assignedComputers->isNotEmpty()) {
                            return $assignedComputers->first()->assigned_to;
                        }

                        if (! empty($record->assigned_to)) {
                            return $record->assigned_to;
                        }

                        return 'Unassigned';
                    }),

                TextColumn::make('department')
                    ->label('Department')
                    ->toggleable()
                    // ->searchable()
                    ->getStateUsing(function (Peripherals $record) {
                        $assignedComputers = $record->assignedComputers;

                        // Only return if there are assigned computers
                        if ($assignedComputers && $assignedComputers->isNotEmpty()) {
                            return $assignedComputers->first()->department_name ?? 'N/A';
                        }

                        if ($record->department_id) {
                            return $record->department?->name ?? 'N/A';
                        }

                        return 'N/A';
                    }),

                TextColumn::make('asset_code')
                    ->wrapHeader()
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('recent_maintenance_date')
                    ->wrapHeader()
                    ->label('Recent Maintenance Date')
                    ->toggleable()
                    ->getStateUsing(function (Peripherals $record): string {
                        $latestMaintenanceDate = $record->maintenanceLogs()
                            ->whereNotNull('maintenance_date')
                            ->max('maintenance_date');

                        if (! $latestMaintenanceDate) {
                            return 'No maintenance yet';
                        }

                        return Carbon::parse($latestMaintenanceDate)->format('M d, Y');
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw(
                            '(select max(maintenance_date) from asset_maintenance_logs where asset_maintenance_logs.maintainable_id = peripherals.id and asset_maintenance_logs.maintainable_type = ?) ' . ($direction === 'asc' ? 'asc' : 'desc'),
                            [Peripherals::class]
                        );
                    }),

                TextColumn::make('days_since_maintenance')
                    ->wrapHeader()
                    ->label('Days Since Maintenance')
                    ->toggleable()
                    ->getStateUsing(function (Peripherals $record): string {
                        $latestMaintenanceDate = $record->maintenanceLogs()
                            ->whereNotNull('maintenance_date')
                            ->max('maintenance_date');

                        if (! $latestMaintenanceDate) {
                            return 'No maintenance yet';
                        }

                        $maintenanceDate = Carbon::parse($latestMaintenanceDate)->startOfDay();
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
                    ->color(function (Peripherals $record): string {
                        $latestMaintenanceDate = $record->maintenanceLogs()
                            ->whereNotNull('maintenance_date')
                            ->max('maintenance_date');

                        if (! $latestMaintenanceDate) {
                            return 'gray';
                        }

                        $days = Carbon::parse($latestMaintenanceDate)
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay(), false);

                        if ($days >= 365) {
                            return 'danger';
                        }

                        if ($days >= 180) {
                            return 'warning';
                        }

                        return 'success';
                    }),

                TextColumn::make('serial_number')
                    ->wrapHeader()
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('model')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date_acquired')
                    ->wrapHeader()
                    ->toggleable()
                    ->date()
                    ->sortable(),

                TextColumn::make('years_in_service')
                    ->wrapHeader()
                    ->toggleable()
                    ->label('Years in Service')
                    ->getStateUsing(function (Peripherals $record) {
                        if (! $record->date_acquired) {
                            return 'N/A';
                        }

                        $diff = \Carbon\Carbon::parse($record->date_acquired)->diff(now());

                        $years = $diff->y;
                        $months = $diff->m;

                        $yearLabel = $years === 1 ? 'year' : 'years';
                        $monthLabel = $months === 1 ? 'month' : 'months';
                        return "{$years} {$yearLabel}, {$months} {$monthLabel}";
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw(
                            "(TIMESTAMPDIFF(MONTH, date_acquired, CURDATE()) / 12) " . ($direction === 'asc' ? 'asc' : 'desc')
                        );
                    }),

                TextColumn::make('description')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->wrapHeader()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->wrapHeader()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', direction: 'desc')
            ->reorderableColumns()

            ->filters([

                Filter::make('has_maintenance')
                    ->label('Has Maintenance')
                    // ->toggle()
                    ->query(fn($query) => $query->whereHas('maintenanceLogs')),

                    SelectFilter::make('replacement_item')
                        ->label('Replacement Item')
                        ->searchable()
                        ->options(function (): array {
                            return OfficeSupplies::query()
                                ->orderBy('name', 'asc')
                                ->get()
                                ->mapWithKeys(function ($supply) {
                                    $baseName = $supply->name ?: 'Supply #' . $supply->id;

                                    $label = $supply->brand
                                        ? $baseName . ' (' . $supply->brand . ')'
                                        : $baseName;

                                    return [$supply->id => $label];
                                })
                                ->toArray();
                        })
                        ->query(function (Builder $query, array $data): Builder {
                            $officeSupplyId = $data['value'] ?? null;

                            if (! $officeSupplyId) {
                                return $query;
                            }

                            return $query->whereHas('maintenanceLogs', function (Builder $maintenanceQuery) use ($officeSupplyId) {
                                $maintenanceQuery
                                    ->where('maintenance_type', 'replacement')
                                    ->where('office_supply_id', (int) $officeSupplyId);
                            });
                        }),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Good Condition' => 'Good Condition',
                        'In Maintenance' => 'In Maintenance',
                        'For Repair' => 'For Repair',
                        'Damaged' => 'Damaged',
                        'Lost' => 'Lost',
                        'Retire' => 'Retire',
                        'Spare' => 'Spare',
                    ]),

                SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(function (): array {
                        $fromAssignments = AssignedComputer::query()
                            ->whereNotNull('assigned_to', 'and')
                            ->where('assigned_to', '!=', '')
                            ->where(function ($query) {
                                $query->whereNotNull('keyboard_id')
                                    ->orWhereNotNull('mouse_id')
                                    ->orWhereNotNull('monitor_id')
                                    ->orWhereNotNull('ups_id');
                            })
                            ->distinct()
                            ->orderBy('assigned_to', 'asc')
                            ->pluck('assigned_to', 'assigned_to')
                            ->toArray();

                        $fromPeripherals = Peripherals::query()
                            ->whereNotNull('assigned_to', 'and')
                            ->where('assigned_to', '!=', '')
                            ->distinct()
                            ->orderBy('assigned_to', 'asc')
                            ->pluck('assigned_to', 'assigned_to')
                            ->toArray();

                        $assigned = $fromAssignments + $fromPeripherals;

                        // Add "Unassigned" option
                        return ['unassigned' => 'Unassigned'] + $assigned;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === 'unassigned') {
                            return $query->whereDoesntHave('assignedKeyboards')
                                ->whereDoesntHave('assignedMice')
                                ->whereDoesntHave('assignedMonitors')
                                ->whereDoesntHave('assignedUps')
                                ->where(function (Builder $q) {
                                    $q->whereNull('assigned_to')
                                        ->orWhere('assigned_to', '');
                                });
                        }

                        if ($value) {
                            return $query->where(function (Builder $q) use ($value) {
                                $q->whereHas('assignedKeyboards', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhereHas('assignedMice', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhereHas('assignedMonitors', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhereHas('assignedUps', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhere('assigned_to', '=', $value);
                            });
                        }

                        return $query;
                    }),
                SelectFilter::make('department')
                    ->label('Department')
                    ->options(function (): array {
                        $fromAssignments = AssignedComputer::query()
                            ->where(function ($query): void {
                                $query->whereNotNull('keyboard_id', 'and')
                                    ->orWhereNotNull('mouse_id', 'and')
                                    ->orWhereNotNull('monitor_id', 'and')
                                    ->orWhereNotNull('ups_id', 'and');
                            })
                            ->whereNotNull('department_id', 'and')
                            ->distinct()
                            ->pluck('department_id')
                            ->toArray();

                        $fromPeripherals = Peripherals::query()
                            ->whereNotNull('department_id', 'and')
                            ->distinct()
                            ->pluck('department_id')
                            ->toArray();

                        $departmentIds = array_unique(array_merge($fromAssignments, $fromPeripherals));

                        $departments = Departments::query()
                            ->whereIn('id', $departmentIds, 'and', false)
                            ->whereNotNull('name', 'and')
                            ->where('name', '!=', '')
                            ->distinct()
                            ->orderBy('name', 'asc')
                            ->pluck('name', 'name')
                            ->toArray();

                        return $departments;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value) {
                            return $query->where(function (Builder $q) use ($value) {
                                $q->whereHas('assignedKeyboards', fn($query) => $query->whereHas('department', fn($departmentQuery) => $departmentQuery->where('name', '=', $value)))
                                    ->orWhereHas('assignedMice', fn($query) => $query->whereHas('department', fn($departmentQuery) => $departmentQuery->where('name', '=', $value)))
                                    ->orWhereHas('assignedMonitors', fn($query) => $query->whereHas('department', fn($departmentQuery) => $departmentQuery->where('name', '=', $value)))
                                    ->orWhereHas('assignedUps', fn($query) => $query->whereHas('department', fn($departmentQuery) => $departmentQuery->where('name', '=', $value)))
                                    ->orWhereHas('department', fn($departmentQuery) => $departmentQuery->where('name', '=', $value));
                            });
                        }

                        return $query;
                    }),
                SelectFilter::make('model')
                    ->label('Model')
                    ->options(function () {
                        return Peripherals::query()
                            ->whereNotNull('model', 'and')
                            ->where('model', '!=', '')
                            ->select('model')
                            ->distinct()
                            ->orderBy('model', 'asc')
                            ->pluck('model', 'model')
                            ->filter(fn($label, $value) => $label !== null && $value !== null) // avoid nulls
                            ->toArray();
                    }),

                SelectFilter::make('month')
                    ->label('Month')
                    ->options([
                        '1' => 'January',
                        '2' => 'February',
                        '3' => 'March',
                        '4' => 'April',
                        '5' => 'May',
                        '6' => 'June',
                        '7' => 'July',
                        '8' => 'August',
                        '9' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $month = $data['value'] ?? null;
                        return $query->when($month, fn(Builder $q, $m) => $q->whereMonth('date_acquired', (int) $m));
                    }),

                SelectFilter::make('year')
                    ->label('Year')
                    ->options(function (): array {
                        return Peripherals::query()
                            ->selectRaw('YEAR(date_acquired) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->filter(fn($value, $key) => !is_null($key) && !is_null($value)) // ensure both key/value are strings
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $year = $data['value'] ?? null;
                        return $query->when($year, fn(Builder $q, $y) => $q->whereYear('date_acquired', (int) $y));
                    }),
            ], layout: FiltersLayout::Modal)->filtersFormColumns(2)
            ->recordActions([

                ActionGroup::make([
                    ViewAction::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->icon('heroicon-o-eye')
                        ->tooltip('View details'),

                    EditAction::make()
                        ->color('primary')
                        ->hiddenLabel()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit record'),

                    Action::make('assign')
                        ->label('Assign')
                        ->color('success')
                        ->hiddenLabel()
                        ->icon('heroicon-o-user-plus')
                        ->tooltip('Assign to user and department')
                        ->visible(fn(Peripherals $record) => $record->assignedComputers->isEmpty())
                        ->form([
                            TextInput::make('assigned_to')
                                ->label('Assigned To')
                                ->required(),
                            Select::make('department_id')
                                ->label('Department')
                                ->options(fn() => Departments::pluck('name', 'id')->toArray())
                                ->searchable()
                                ->required(),
                        ])
                        ->fillForm(function (Peripherals $record): array {
                            $assignment = $record->assignedComputers->first();

                            return [
                                'assigned_to' => $assignment?->assigned_to ?: $record->assigned_to,
                                'department_id' => $assignment?->department_id ?: $record->department_id,
                            ];
                        })
                        ->action(function (Peripherals $record, array $data): void {
                            $record->assigned_to = $data['assigned_to'];
                            $record->department_id = (int) $data['department_id'];
                            $record->save();
                        })->successNotificationTitle('Peripheral Assigned Successfully'),

                    Action::make('maintenance')
                        ->color('primary')
                        ->hiddenLabel()
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->tooltip('Maintenance history')
                        ->url(fn(Peripherals $record): string => PeripheralsResource::getUrl('edit', [
                            'record' => $record,
                            'relation' => 'maintenance',
                        ])),

                    Action::make('changeStatus')
                        ->color('primary')
                        ->label('Set Status')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Good Condition' => 'Good Condition',
                                    'In Maintenance' => 'In Maintenance',
                                    'For Repair' => 'For Repair',
                                    'Damaged' => 'Damaged',
                                    'Lost' => 'Lost',
                                    'Retire' => 'Retire',
                                    'Spare' => 'Spare',
                                ])
                                ->required()
                                ->default(fn($record) => $record->status),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->update([
                                'status' => $data['status'],
                            ]);
                        })
                        ->successNotificationTitle('Status updated successfully'),

                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('top-start')
                    ->color('primary')

            ], position: RecordActionsPosition::BeforeCells)

            ->headerActions([
                \Filament\Actions\Action::make('export_csv')
                    ->label('Export all records')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        // Get the current page URL with all query parameters
                        $currentUrl = request()->fullUrl();
                        $parsedUrl = parse_url($currentUrl);

                        // Parse query parameters
                        $queryParams = [];
                        if (isset($parsedUrl['query'])) {
                            parse_str($parsedUrl['query'], $queryParams);
                        }

                        // Build export URL with current filters
                        $exportUrl = route('export.peripherals');
                        $exportParams = [];

                        // Extract search parameter from tableSearch
                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        // Build final URL
                        if (!empty($exportParams)) {
                            $exportUrl .= '?' . http_build_query($exportParams);
                        }

                        // Redirect to export URL
                        return redirect($exportUrl);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.peripherals') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
