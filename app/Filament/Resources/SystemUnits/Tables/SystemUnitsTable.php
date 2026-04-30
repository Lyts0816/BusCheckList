<?php

namespace App\Filament\Resources\SystemUnits\Tables;

use App\Models\SystemUnit;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\SystemUnits\SystemUnitResource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\Size;

class SystemUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(),

                TextColumn::make('assignedComputer.assigned_to')
                    ->label('Assigned To')
                    ->searchable()
                    ->toggleable()
                    ->getStateUsing(function (SystemUnit $record) {
                        return $record->assignedComputer?->assigned_to ?? 'Unassigned';
                    }),

                TextColumn::make('assignedComputer.department')
                    ->label('Department')
                    ->searchable()
                    ->toggleable()
                    ->getStateUsing(function (SystemUnit $record) {
                        return $record->assignedComputer?->department_name ?? 'no-department';
                    }),

                TextColumn::make('asset_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(function (SystemUnit $record): ?string {
                        $ip = $record->ip_address;

                        if (blank($ip)) {
                            return 'gray';
                        }

                        $hasDuplicate = SystemUnit::query()
                            ->where('ip_address', $ip)
                            ->whereKeyNot($record->getKey())
                            ->exists();

                        return $hasDuplicate ? 'danger' : 'gray';
                    })
                    ->toggleable(),

                TextColumn::make('model')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('date_aquired')
                    ->date()
                    ->toggleable(),

                TextColumn::make('years_in_service')
                    ->label('Years in Service')
                    ->toggleable()
                    ->getStateUsing(function (SystemUnit $record) {
                        if (! $record->date_aquired) {
                            return 'N/A';
                        }

                        $diff = \Carbon\Carbon::parse($record->date_aquired)->diff(now());

                        $years = $diff->y;
                        $months = $diff->m;

                        $yearLabel = $years === 1 ? 'year' : 'years';
                        $monthLabel = $months === 1 ? 'month' : 'months';
                        return "{$years} {$yearLabel}, {$months} {$monthLabel}";
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw(
                            "(TIMESTAMPDIFF(MONTH, date_aquired, CURDATE()) / 12) " . ($direction === 'asc' ? 'asc' : 'desc')
                        );
                    }),

                TextColumn::make('OS')
                    ->label('OS')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('storage')
                    ->label('Storage')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('processor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(),

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

                Filter::make('duplicate_ip_address')
                    ->label('Duplicate IP Address')
                    ->query(function (Builder $query): Builder {
                        return $query
                            ->whereNotNull('ip_address', 'and')
                            ->where('ip_address', '!=', '')
                            ->whereIn('ip_address', SystemUnit::query()
                                ->select('ip_address')
                                ->whereNotNull('ip_address', 'and')
                                ->where('ip_address', '!=', '')
                                ->groupBy('ip_address')
                                ->havingRaw('COUNT(*) > 1', []));
                    }),

                SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(function (): array {
                        $assigned = \App\Models\AssignedComputer::query()
                            ->whereNotNull('assigned_to', 'and')
                            ->where('assigned_to', '!=', '')
                            ->distinct()
                            ->orderBy('assigned_to', 'asc')
                            ->pluck('assigned_to', 'assigned_to')
                            ->toArray();

                        return ['unassigned' => 'Unassigned'] + $assigned;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === 'unassigned') {
                            return $query->whereDoesntHave('assignedComputer');
                        }

                        if ($value) {
                            return $query->whereHas('assignedComputer', function (Builder $q) use ($value) {
                                $q->where('assigned_to', $value);
                            });
                        }

                        return $query;
                    }),

                SelectFilter::make('department')
                    ->label('Department')
                    ->options(function (): array {
                        $assigned = \App\Models\AssignedComputer::query()
                            ->whereNotNull('department', 'and')
                            ->where('department', '!=', '')
                            ->distinct()
                            ->orderBy('department', 'asc')
                            ->pluck('department', 'department')
                            ->toArray();

                        return ['unassigned' => 'Unassigned'] + $assigned;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === 'unassigned') {
                            return $query->whereDoesntHave('assignedComputer');
                        }

                        if ($value) {
                            return $query->whereHas('assignedComputer', function (Builder $q) use ($value) {
                                $q->where('department', $value);
                            });
                        }

                        return $query;
                    }),

                SelectFilter::make('model')
                    ->label('Model')
                    ->options(fn() => SystemUnit::query()
                        ->whereNotNull('model', 'and')
                        ->where('model', '!=', '')
                        ->distinct()
                        ->orderBy('model', 'asc')
                        ->pluck('model', 'model')
                        ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                        ->toArray()),

                SelectFilter::make('OS')
                    ->label('OS')
                    ->options(fn() => SystemUnit::query()
                        ->whereNotNull('OS', 'and')
                        ->where('OS', '!=', '')
                        ->distinct()
                        ->orderBy('OS', 'asc')
                        ->pluck('OS', 'OS')
                        ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                        ->toArray()),

                SelectFilter::make('processor')
                    ->label('Processor')
                    ->options(fn() => SystemUnit::query()
                        ->whereNotNull('processor', 'and')
                        ->where('processor', '!=', '')
                        ->distinct()
                        ->orderBy('processor', 'asc')
                        ->pluck('processor', 'processor')
                        ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                        ->toArray()),

                SelectFilter::make('ram')
                    ->label('RAM')
                    ->options(fn() => SystemUnit::query()
                        ->whereNotNull('ram', 'and')
                        ->where('ram', '!=', '')
                        ->distinct()
                        ->orderBy('ram', 'asc')
                        ->pluck('ram', 'ram')
                        ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                        ->toArray()),

                SelectFilter::make('storage')
                    ->label('Storage')
                    ->options(fn() => SystemUnit::query()
                        ->whereNotNull('storage', 'and')
                        ->where('storage', '!=', '')
                        ->distinct()
                        ->orderBy('storage', 'asc')
                        ->pluck('storage', 'storage')
                        ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                        ->toArray()),

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
                        return $query->when($month, fn(Builder $q, $m) => $q->whereMonth('date_aquired', (int) $m));
                    }),

                SelectFilter::make('year')
                    ->label('Year')
                    ->options(function (): array {
                        return SystemUnit::query()
                            ->selectRaw('YEAR(date_aquired) as year')
                            ->whereNotNull('date_aquired', 'and')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $year = $data['value'] ?? null;
                        return $query->when($year, fn(Builder $q, $y) => $q->whereYear('date_aquired', (int) $y));
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
                        ->url(fn(SystemUnit $record): string => SystemUnitResource::getUrl('edit', [
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
                        $exportUrl = route('export.system-units');
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

                BulkActionGroup::make([]),
            ])
            ;
    }
}