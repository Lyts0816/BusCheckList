<?php

namespace App\Filament\Resources\Printers\Tables;

use App\Models\Printer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkAction;

class PrintersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('department')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('printer_host')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('printer_model')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('printer_asset_code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('printer_serial_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_aquired')
                    ->date()
                    ->label('Date Acquired')
                    ->sortable(),
                TextColumn::make('description'),
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
                SelectFilter::make('printer_model')
                    ->label('Model')
                    ->options(fn () => Printer::query()
                        ->whereNotNull('printer_model')
                        ->where('printer_model', '!=', '')
                        ->select('printer_model')
                        ->distinct()
                        ->orderBy('printer_model')
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

                        return $query->when($month, fn (Builder $q, $m) => $q->whereMonth('date_aquired', (int) $m));
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

                        return $query->when($year, fn (Builder $q, $y) => $q->whereYear('date_aquired', (int) $y));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
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
                            if ($key === 'tableFilters[department][value]') {
                                $exportParams['department'] = $value;
                                
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
