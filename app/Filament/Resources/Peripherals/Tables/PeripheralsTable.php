<?php

namespace App\Filament\Resources\Peripherals\Tables;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use App\Models\Peripherals;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;
use Filament\Support\Enums\Size;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Enums\FiltersLayout;

use Filament\Tables\Enums\RecordActionsPosition;


class PeripheralsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(),

                TextColumn::make('item_type')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('assignedComputers.assigned_to')
                    ->label('Assigned To')
                    ->toggleable()
                    // ->searchable()
                    ->getStateUsing(function (Peripherals $record) {
                        $assignedComputers = $record->assignedComputers;

                        // Only return if there are assigned computers
                        if ($assignedComputers && $assignedComputers->isNotEmpty()) {
                            return $assignedComputers->first()->assigned_to;
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
                            return $assignedComputers->first()->department;
                        }

                        return 'N/A';
                    }),

                TextColumn::make('asset_code')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('recent_maintenance_date')
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
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('model')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('date_acquired')
                    ->toggleable()
                    ->date()
                    ->sortable(),

                TextColumn::make('years_in_service')
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', direction: 'desc')

            ->filters([
                SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(function (): array {
                        // Get all unique assigned users from peripherals
                        $assigned = \App\Models\AssignedComputer::query()
                            ->whereNotNull('assigned_to')
                            ->where('assigned_to', '!=', '')
                            ->where(function ($query) {
                                $query->whereNotNull('keyboard_id')
                                    ->orWhereNotNull('mouse_id')
                                    ->orWhereNotNull('monitor_id')
                                    ->orWhereNotNull('ups_id');
                            })
                            ->distinct()
                            ->orderBy('assigned_to')
                            ->pluck('assigned_to', 'assigned_to')
                            ->toArray();

                        // Add "Unassigned" option
                        return ['unassigned' => 'Unassigned'] + $assigned;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === 'unassigned') {
                            return $query->whereDoesntHave('assignedKeyboards')
                                ->whereDoesntHave('assignedMice')
                                ->whereDoesntHave('assignedMonitors')
                                ->whereDoesntHave('assignedUps');
                        }

                        if ($value) {
                            return $query->where(function (Builder $q) use ($value) {
                                $q->whereHas('assignedKeyboards', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhereHas('assignedMice', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhereHas('assignedMonitors', fn($query) => $query->where('assigned_to', $value))
                                    ->orWhereHas('assignedUps', fn($query) => $query->where('assigned_to', $value));
                            });
                        }

                        return $query;
                    }),
                SelectFilter::make('department')
                    ->label('Department')
                    ->options(function (): array {
                        // Get all unique departments from assigned computers with peripherals
                        $departments = \App\Models\AssignedComputer::query()
                            ->whereNotNull('department')
                            ->where('department', '!=', '')
                            ->where(function ($query) {
                                $query->whereNotNull('keyboard_id')
                                    ->orWhereNotNull('mouse_id')
                                    ->orWhereNotNull('monitor_id')
                                    ->orWhereNotNull('ups_id');
                            })
                            ->distinct()
                            ->orderBy('department')
                            ->pluck('department', 'department')
                            ->toArray();

                        return $departments;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value) {
                            return $query->where(function (Builder $q) use ($value) {
                                $q->whereHas('assignedKeyboards', fn($query) => $query->where('department', $value))
                                    ->orWhereHas('assignedMice', fn($query) => $query->where('department', $value))
                                    ->orWhereHas('assignedMonitors', fn($query) => $query->where('department', $value))
                                    ->orWhereHas('assignedUps', fn($query) => $query->where('department', $value));
                            });
                        }

                        return $query;
                    }),
                SelectFilter::make('model')
                    ->label('Model')
                    ->options(function () {
                        return Peripherals::query()
                            ->whereNotNull('model')
                            ->where('model', '!=', '')
                            ->select('model')
                            ->distinct()
                            ->orderBy('model')
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

                    Action::make('maintenance')
                        ->color('warning')
                        ->hiddenLabel()
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->tooltip('Maintenance history')
                        ->url(fn(Peripherals $record): string => PeripheralsResource::getUrl('edit', [
                            'record' => $record,
                            'relation' => 'maintenance',
                        ])),
                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
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
