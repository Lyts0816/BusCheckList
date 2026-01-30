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

                TextColumn::make('km_run')
                    ->label('KM Run')
                    ->sortable(),
                TextColumn::make('total_travel_time_minutes')
                    ->label('Total Travel Time')
                    ->formatStateUsing(fn($state) => 
                        $state ? intdiv($state, 60) . ' hour' . (intdiv($state, 60) !== 1 ? 's' : '') . 
                        ' and ' . ($state % 60) . ' minute' . (($state % 60) !== 1 ? 's' : '') : '0 minutes'
                    )
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                    ViewAction::make(),
                    EditAction::make()
                        ->mutateRecordDataUsing(function (array $data): array {
                            // Convert total_travel_time_minutes back to hours + minutes for editing
                            $totalTravelMinutes = $data['total_travel_time_minutes'] ?? 0;
                            $data['hours'] = intdiv($totalTravelMinutes, 60);
                            $data['minutes'] = $totalTravelMinutes % 60;
                            
                            // Convert total_add_time_minutes back to add_time_hours + add_time_minutes for editing
                            $totalAddMinutes = $data['total_add_time_minutes'] ?? 0;
                            $data['add_time_hours'] = intdiv($totalAddMinutes, 60);
                            $data['add_time_minutes'] = $totalAddMinutes % 60;
                            
                            return $data;
                        })
                        ->using(function (array $data, $record): void {
                            // Convert hours + minutes to total_travel_time_minutes before saving
                            $data['total_travel_time_minutes'] = 
                                (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);
                            
                            // Convert add_time_hours + add_time_minutes to total_add_time_minutes before saving
                            $data['total_add_time_minutes'] = 
                                (($data['add_time_hours'] ?? 0) * 60) + ($data['add_time_minutes'] ?? 0);
                            
                            // Remove temporary fields
                            unset($data['hours'], $data['minutes'], $data['add_time_hours'], $data['add_time_minutes']);
                            
                            $record->update($data);
                        }),
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
