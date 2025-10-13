<?php

namespace App\Filament\Resources\SystemUnits\Tables;

use App\Models\SystemUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;

class SystemUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('asset_code')
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('date_aquired')
                    ->date()
                    ->sortable(),
                TextColumn::make('OS')
                    ->label('OS')
                    ->sortable(),
                TextColumn::make('windows_serial_number')
                    ->sortable(),
                TextColumn::make('microsoft_serial_number')
                    ->searchable(),
                TextColumn::make('ram')
                    ->label('RAM')
                    ->sortable(),
                TextColumn::make('storage')
                    ->label('Storage')
                    ->sortable(),
                TextColumn::make('processor')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', direction: 'desc')
            ->filters([
            SelectFilter::make('model')
                ->label('Model')
                ->options(fn() => SystemUnit::query()
                    ->whereNotNull('model')
                    ->where('model', '!=', '')
                    ->distinct()
                    ->orderBy('model')
                    ->pluck('model', 'model')
                    ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                    ->toArray()),

            SelectFilter::make('OS')
                ->label('OS')
                ->options(fn() => SystemUnit::query()
                    ->whereNotNull('OS')
                    ->where('OS', '!=', '')
                    ->distinct()
                    ->orderBy('OS')
                    ->pluck('OS', 'OS')
                    ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                    ->toArray()),

            SelectFilter::make('processor')
                ->label('Processor')
                ->options(fn() => SystemUnit::query()
                    ->whereNotNull('processor')
                    ->where('processor', '!=', '')
                    ->distinct()
                    ->orderBy('processor')
                    ->pluck('processor', 'processor')
                    ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                    ->toArray()),

            SelectFilter::make('ram')
                ->label('RAM')
                ->options(fn() => SystemUnit::query()
                    ->whereNotNull('ram')
                    ->where('ram', '!=', '')
                    ->distinct()
                    ->orderBy('ram')
                    ->pluck('ram', 'ram')
                    ->filter(fn($value, $key) => !is_null($key) && !is_null($value))
                    ->toArray()),

            SelectFilter::make('storage')
                ->label('Storage')
                ->options(fn() => SystemUnit::query()
                    ->whereNotNull('storage')
                    ->where('storage', '!=', '')
                    ->distinct()
                    ->orderBy('storage')
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
                        ->whereNotNull('date_aquired')
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
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.system-units') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
