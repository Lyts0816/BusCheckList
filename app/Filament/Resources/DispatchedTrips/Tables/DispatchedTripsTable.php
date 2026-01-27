<?php

namespace App\Filament\Resources\DispatchedTrips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateTimeColumn;

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

                TextColumn::make('date_time_of_arrival')
                    ->dateTime('M d, Y h:i A')
                    ->label('Arrival')
                    ->sortable(),

                TextColumn::make('passengers_on_board')
                    ->label('Passengers')
                    ->sortable(),

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
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
