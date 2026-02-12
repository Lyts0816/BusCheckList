<?php

namespace App\Filament\Resources\DispatchedTrips\Tables;

use App\Models\BusNumber;
use App\Models\Routes;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\RecordActionsPosition;

class DispatchedTripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip_number')
                    ->label('Trip Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('dispatchSheet.dispatch_date')
                    ->label('Dispatch Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('busNumber.bus_number')
                    ->label('Bus Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('snap_drivers')
                    ->label('Driver')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('snap_conductors')
                    ->label('Conductor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('time_of_arrival')
                    ->time('h:i A')
                    ->label('Time of Arrival')
                    ->sortable(),

                TextColumn::make('dispatchSheet.route.from')
                    ->label('Route')
                    ->formatStateUsing(function ($record) {
                        $route = $record->dispatchSheet?->route;
                        return $route ? ($route->from . ' - ' . $route->to) : 'Unknown';
                    })
                    ->sortable()
                    ->searchable(),


            ])
            ->defaultSort('trip_number', 'desc')
            ->filters([
                
                SelectFilter::make('bus_number')
                    ->label('Bus Number')
                    ->options(fn () => BusNumber::query()
                        ->orderBy('bus_number')
                        ->pluck('bus_number', 'bus_number'))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        $value = $data['value'] ?? null;

                        return $query->when(
                            $value,
                            fn ($query) => $query->whereHas('busNumber', fn ($q) => $q->where('bus_number', $value))
                        );
                    }),

                SelectFilter::make('route_id')
                    ->label('Routes')
                    ->options(fn () => Routes::query()
                        ->orderBy('from')
                        ->get()
                        ->mapWithKeys(fn ($route) => [$route->id => $route->from . ' - ' . $route->to]))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        $value = $data['value'] ?? null;

                        return $query->when(
                            $value,
                            fn ($query) => $query->whereHas('dispatchSheet', fn ($q) => $q->where('route_id', $value))
                        );
                    }),

                Filter::make('dispatch_date')
                    ->label('Dispatch Date')
                    ->schema([
                        DatePicker::make('dispatch_from')->label('From'),
                        DatePicker::make('dispatch_until')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['dispatch_from'] ?? null,
                                fn($query, $date) => $query->whereHas('dispatchSheet', fn ($q) => $q->whereDate('dispatch_date', '>=', $date))
                            )
                            ->when(
                                $data['dispatch_until'] ?? null,
                                fn($query, $date) => $query->whereHas('dispatchSheet', fn ($q) => $q->whereDate('dispatch_date', '<=', $date))
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ],position: RecordActionsPosition::BeforeCells)
            ->headerActions([

                \Filament\Actions\Action::make('export_csv')
                    ->label('Export CSV')
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
                        $exportUrl = route('export.dispatched-trips');
                        $exportParams = [];

                        // Extract search parameter from tableSearch
                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        // Extract other relevant filters
                        foreach ($queryParams as $key => $value) {
                            if (strpos($key, 'tableFilters') === 0 && !empty($value)) {
                            }
                        }


                        if (!empty($exportParams)) {
                            $exportUrl .= '?' . http_build_query($exportParams);
                        }

                        // Redirect to export URL
                        return redirect($exportUrl);
                    }),

                \Filament\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document')
                    ->color('info')
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
                        $exportUrl = route('export.dispatched-trips-pdf');
                        $exportParams = [];

                        // Extract search parameter from tableSearch
                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        // Extract other relevant filters
                        foreach ($queryParams as $key => $value) {
                            if (strpos($key, 'tableFilters') === 0 && !empty($value)) {
                            }
                        }


                        if (!empty($exportParams)) {
                            $exportUrl .= '?' . http_build_query($exportParams);
                        }

                        // Redirect to export URL
                        return redirect($exportUrl);
                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([

                    BulkAction::make('export_selected_csv')
                        ->label('Export Selected (CSV)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.dispatched-trips') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),

                    BulkAction::make('export_selected_pdf')
                        ->label('Export Selected (PDF)')
                        ->icon('heroicon-o-document')
                        ->color('info')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.dispatched-trips-pdf') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
