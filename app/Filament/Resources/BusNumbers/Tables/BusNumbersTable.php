<?php

namespace App\Filament\Resources\BusNumbers\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class BusNumbersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bus_number')
                    ->toggleable()
                    ->label('Bus Number')
                    ->searchable(),

                TextColumn::make('bus_class')
                    ->toggleable()
                    ->label('Bus Class')
                    ->sortable(),

                TextColumn::make('driver.driver_name')
                    ->toggleable()
                    ->label('Driver Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('conductor.conductor_name')
                    ->toggleable()
                    ->label('Conductor Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bus_model')
                    ->toggleable()
                    ->label('Bus Model'),

                TextColumn::make('seat_capacity')
                    ->toggleable()
                    ->label('Seating Capacity'),
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
