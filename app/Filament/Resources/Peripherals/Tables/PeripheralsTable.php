<?php

namespace App\Filament\Resources\Peripherals\Tables;

use App\Models\Peripherals;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;

class PeripheralsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('item_type')
                    ->searchable(),
                TextColumn::make('asset_code')
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('date_acquired')
                    ->date()
                    ->sortable(),
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
