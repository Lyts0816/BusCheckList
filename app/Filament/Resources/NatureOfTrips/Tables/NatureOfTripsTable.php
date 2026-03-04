<?php

namespace App\Filament\Resources\NatureOfTrips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NatureOfTripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nature_of_trip_name')
                    ->toggleable()
                    ->label('Nature of Trip Name')
                    ->searchable(),

                TextColumn::make('description')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('remarks')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->toggleable()
                    ->dateTime()
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
