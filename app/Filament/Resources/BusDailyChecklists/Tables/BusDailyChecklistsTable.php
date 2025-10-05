<?php

namespace App\Filament\Resources\BusDailyChecklists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BusDailyChecklist;
use Filament\Tables\Filters\Filter;
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

use Filament\Forms\Components\DatePicker;
use App\Filament\Exports\BusDailyChecklistExporter;

class BusDailyChecklistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->hidden(),
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
                    ->form([
                        DatePicker::make('date')
                            ->label('Specific Date')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('check_date', $date),
                        );
                    }),

                TernaryFilter::make('checked')
                        ->label('Checked')
                        ->boolean()
                        
             ]
            )
            ->headerActions([
                ExportAction::make()
                    ->label('Export')
                    ->exporter(BusDailyChecklistExporter::class)
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
