<?php

namespace App\Filament\Resources\Printers\Tables;

use App\Filament\Resources\Printers\PrintersResource;
use App\Models\Printer;
use App\Models\Departments;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\Size;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;

use Filament\Tables\Enums\RecordActionsPosition;

class PrintersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('maintenance_logs_count')
                    ->toggleable()
                    ->sortable()
                    ->wrapHeader()
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

                TextColumn::make('department.name')
                    ->toggleable()
                    ->label('Department')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('printer_host')
                    ->wrapHeader()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('printer_model')
                    ->wrapHeader()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('asset_code')
                    ->wrapHeader()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('printer_serial_number')
                    ->wrapHeader()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date_aquired')
                    ->wrapHeader()
                    ->toggleable()
                    ->date()
                    ->label('Date Acquired')
                    ->sortable(),

                TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),



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
            ])->reorderableColumns()
            ->searchPlaceholder('Search')

            ->defaultSort('id', direction: 'desc')

            ->filters([

                Filter::make('has_maintenance')
                    ->label('Has Maintenance')
                    ->toggle()
                    ->query(fn($query) => $query->whereHas('maintenanceLogs')),

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

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->options(
                        fn() => Departments::query()
                            ->orderBy('name', 'asc')
                            ->pluck('name', 'id')
                            ->toArray()
                    ),

                SelectFilter::make('printer_model')
                    ->label('Model')
                    ->options(
                        fn() => Printer::query()
                            ->whereNotNull('printer_model', 'and')
                            ->where('printer_model', '!=', '')
                            ->select('printer_model')
                            ->distinct()
                            ->orderBy('printer_model', 'asc')
                            ->pluck('printer_model', 'printer_model')
                            ->toArray()
                    ),

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
                        return Printer::query()
                            ->selectRaw('YEAR(date_aquired) as year')
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
            ->filtersFormMaxHeight('400px')

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
                        ->color('primary')
                        ->hiddenLabel()
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->tooltip('Maintenance history')
                        ->url(fn(Printer $record): string => PrintersResource::getUrl('edit', [
                            'record' => $record,
                            'relation' => 'maintenance',
                        ])),

                    Action::make('changeStatus')
                        ->label('Set Status')
                        ->color('primary')
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
                    ->dropdownPlacement('bottom-start')
                    ->color('primary')
            ], position: RecordActionsPosition::BeforeCells)

            ->headerActions([
                \Filament\Actions\Action::make('export_csv')
                    ->label('Export all record')
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
                        $exportUrl = route('export.printers');
                        $exportParams = [];

                        // Extract search parameter from tableSearch
                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        // Extract other relevant filters
                        foreach ($queryParams as $key => $value) {
                            if (strpos($key, 'tableFilters') === 0 && !empty($value)) {
                                // Parse Filament filter format
                                if ($key === 'tableFilters[department_id][value]') {
                                    $exportParams['department_id'] = $value;
                                }
                            }
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
                            $exportUrl = route('export.printers') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
