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

                TextColumn::make('time_of_arrival')
                    ->time('h:i A')
                    ->label('Arrivals')
                    ->sortable(),


                TextColumn::make('dispatchSheet.route.from')
                    ->label('Routes')
                    ->formatStateUsing(function ($record) {
                        $route = $record->dispatchSheet?->route;

                        return $route ? ($route->from . ' - ' . $route->to) : 'Unknown';
                    })
                    ->sortable(),

                TextColumn::make('busNumber.bus_number')
                    ->label('Bus Numbers')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('busNumber.bus_class')
                    ->label('Bus Classes')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('time_of_departure')
                    ->time('h:i A')
                    ->label('Departures')
                    ->sortable(),

                TextColumn::make('km_run')
                    ->label('KM Runs')
                    ->sortable(),
                TextColumn::make('total_travel_time_minutes')
                    ->label('Total Travel Time')
                    ->formatStateUsing(
                        fn($state) =>
                        $state ? intdiv($state, 60) . ' hour' . (intdiv($state, 60) !== 1 ? 's' : '') .
                            ' and ' . ($state % 60) . ' minute' . (($state % 60) !== 1 ? 's' : '') : '0 minutes'
                    )
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('trip_number', 'desc')
            ->filters([
                SelectFilter::make('bus_class')
                    ->label('Bus Classes')
                    ->options(fn () => BusNumber::query()
                        ->whereNotNull('bus_class')
                        ->distinct()
                        ->orderBy('bus_class')
                        ->pluck('bus_class', 'bus_class'))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        $value = $data['value'] ?? null;

                        return $query->when(
                            $value,
                            fn ($query) => $query->whereHas('busNumber', fn ($q) => $q->where('bus_class', $value))
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
                        DatePicker::make('arrival_from')->label('From'),
                        DatePicker::make('arrival_until')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['arrival_from'] ?? null,
                                fn($query, $date) => $query->whereHas('dispatchSheet', fn ($q) => $q->whereDate('dispatch_date', '>=', $date))
                            )
                            ->when(
                                $data['arrival_until'] ?? null,
                                fn($query, $date) => $query->whereHas('dispatchSheet', fn ($q) => $q->whereDate('dispatch_date', '<=', $date))
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
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

            ])
            ->toolbarActions([
                BulkActionGroup::make([

                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.dispatched-trips') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
