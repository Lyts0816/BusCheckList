<?php

namespace App\Filament\Resources\Transfers\Tables;

use App\Models\Transfer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No.')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('date')
                    ->label('Date transferred')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Transferred' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Pending' => 'heroicon-m-clock',
                        'Transferred' => 'heroicon-m-check-circle',
                        'Cancelled' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->sortable(),

                // "To" is the main text; "From" shows underneath. Searching this column checks both.
                TextColumn::make('to')
                    ->label('Transferred to')
                    ->description(fn ($record): string => 'From: ' . $record->from)
                    ->weight('medium')
                    ->searchable(['to', 'from'])
                    ->sortable(),

                // Searching here also looks at asset codes and serial numbers.
                TextColumn::make('items.item_name')
                    ->label('Items')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable(['items.item_name', 'items.asset_code', 'items.serial_number']),

                TextColumn::make('items_count')
                    ->label('No. of items')
                    ->counts('items')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('prepared_by')
                    ->label('Prepared by')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('received_by')
                    ->label('Received by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('guard_on_duty')
                    ->label('Guard on duty')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // Searching
            ->searchPlaceholder('Search department, person, item, asset code or serial no.')
            ->persistSearchInSession()

            // Filters (shown above the table so users don't have to hunt for them)
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'Pending' => 'Pending',
                        'Transferred' => 'Transferred',
                        'Cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('to')
                    ->label('Transferred to')
                    ->searchable()
                    ->options(fn () => Transfer::query()
                        ->whereNotNull('to')->distinct()->orderBy('to')
                        ->pluck('to', 'to')->all()),

                SelectFilter::make('prepared_by')
                    ->label('Prepared by')
                    ->searchable()
                    ->options(fn () => Transfer::query()
                        ->whereNotNull('prepared_by')->distinct()->orderBy('prepared_by')
                        ->pluck('prepared_by', 'prepared_by')->all()),

                Filter::make('date_range')
                    ->label('Date range')
                    ->schema([
                        DatePicker::make('date_from')->label('From date')->native(false),
                        DatePicker::make('date_until')->label('Until date')->native(false),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['date_from'] ?? null, fn (Builder $q, $d) => $q->whereDate('date', '>=', $d))
                        ->when($data['date_until'] ?? null, fn (Builder $q, $d) => $q->whereDate('date', '<=', $d)))
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_from'] ?? null) {
                            $indicators[] = Indicator::make('From ' . Carbon::parse($data['date_from'])->format('d M Y'))
                                ->removeField('date_from');
                        }

                        if ($data['date_until'] ?? null) {
                            $indicators[] = Indicator::make('Until ' . Carbon::parse($data['date_until'])->format('d M Y'))
                                ->removeField('date_until');
                        }

                        return $indicators;
                    })
                    ->columnSpan(2),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(4)
            ->deferFilters(false)          // apply filters immediately
            ->persistFiltersInSession()

            // Grouping
            ->groups([
                Group::make('status')
                    ->label('Status')
                    ->collapsible(),

                Group::make('to')
                    ->label('Transferred to')
                    ->collapsible(),

                Group::make('from')
                    ->label('Transferred from')
                    ->collapsible(),

                Group::make('prepared_by')
                    ->label('Prepared by')
                    ->collapsible(),

                Group::make('date')
                    ->label('Day')
                    ->date()
                    ->collapsible(),
            ])

            // Easier to scan and use
            ->defaultSort('id', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->recordAction(ViewAction::class)   // clicking a row opens the details
            ->emptyStateHeading('No transfers found')
            ->emptyStateDescription('Try a different search or clear the filters, or create a new transfer.')

            ->recordActions([
                ViewAction::make()->modalWidth('7xl'),
                EditAction::make()->modalWidth('7xl'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}