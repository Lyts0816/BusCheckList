<?php

namespace App\Filament\Resources\DispatchTrips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispatchTripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip_number')
                    ->searchable(),
                TextColumn::make('from')
                    ->searchable(),
                TextColumn::make('to')
                    ->searchable(),
                TextColumn::make('bus_number')
                    ->searchable(),
                TextColumn::make('bus_class')
                    ->searchable(),
                TextColumn::make('nature_of_trip')
                    ->searchable(),
                TextColumn::make('date_time_in_terminal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_time_of_parking')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_time_of_departure')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_time_of_arrival')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('idle_time_start')
                    ->time()
                    ->sortable(),
                TextColumn::make('idle_time_end')
                    ->time()
                    ->sortable(),
                TextColumn::make('driver')
                    ->searchable(),
                TextColumn::make('conductor')
                    ->searchable(),
                TextColumn::make('total_travel_time_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_add_time_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('km_run')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('ticket_number')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('passengers_on_board')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('baggage_amount')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('baggage_ticket_no')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('remarks')
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
            ->filters([
                //
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
