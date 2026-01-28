<?php

namespace App\Filament\Resources\DispatchedTrips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
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

                TextColumn::make('date_time_of_arrival')
                    ->dateTime('M d, Y h:i A')
                    ->label('Arrival')
                    ->sortable(),


                TextColumn::make('route.from')
                    ->label('Route')
                    ->formatStateUsing(fn($record) => $record->route->from . ' - ' . $record->route->to)
                    ->sortable(),

                TextColumn::make('busNumber.bus_number')
                    ->label('Bus Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('busClass.class_name')
                    ->label('Bus Class')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('driver.driver_name')
                    ->label('Driver')
                    ->sortable(),

                TextColumn::make('conductor.conductor_name')
                    ->label('Conductor')
                    ->sortable(),

                TextColumn::make('date_time_of_departure')
                    ->dateTime('M d, Y h:i A')
                    ->label('Departure')
                    ->sortable(),

                // TextColumn::make('passengers_on_board')
                //     ->label('Passengers')
                //     ->sortable(),

                TextColumn::make('km_run')
                    ->label('KM Run')
                    ->sortable(),
            ])
            ->filters([
                //
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
                        $exportUrl = route('export.dispatched-trips');
                        $exportParams = [];

                        // Extract search parameter from tableSearch
                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        // Extract other relevant filters
                        foreach ($queryParams as $key => $value) {
                            if (strpos($key, 'tableFilters') === 0 && !empty($value)) {
                                // Parse Filament filter format if you have filters
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
                    // DeleteBulkAction::make(),
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
