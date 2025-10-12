<?php

namespace App\Filament\Resources\BusDailyChecklists\Tables;

use App\Models\BusDailyChecklist;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;

class BusDailyChecklistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('bus.bus_number')
                    ->label('Bus Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('check_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('checked')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchPlaceholder('Search')
            ->defaultSort('id', direction: 'desc')
            ->filters([
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

                        return $query->when($month, fn (Builder $q, $m) => $q->whereMonth('check_date', (int) $m));
                    }),

                SelectFilter::make('year')
                    ->label('Year')
                    ->options(function (): array {
                        return BusDailyChecklist::query()
                            ->selectRaw('YEAR(check_date) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $year = $data['value'] ?? null;

                        return $query->when($year, fn (Builder $q, $y) => $q->whereYear('check_date', (int) $y));
                    }),

                Filter::make('check_date')
                    ->schema([
                        DatePicker::make('date')
                            ->label('Specific Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('check_date', $date),
                        );
                    }),

                TernaryFilter::make('checked')
                    ->label('Checked')
                    ->boolean(),

            ]
            )
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
                        $exportUrl = route('export.bus-daily-checklist');
                        $exportParams = [];
                        
                        // Extract search parameter
                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }
                        
                        // Extract filters
                        foreach ($queryParams as $key => $value) {
                            if (strpos($key, 'tableFilters') === 0 && !empty($value)) {
                                // Parse Bus Daily Checklist specific filters
                                if ($key === 'tableFilters[checked][value]') {
                                    $exportParams['checked'] = $value;
                                }
                                if ($key === 'tableFilters[year][value]') {
                                    $exportParams['year'] = $value;
                                }
                                if ($key === 'tableFilters[check_date][date]') {
                                    $exportParams['date'] = $value;
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
                            $exportUrl = route('export.bus-daily-checklist') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
