<?php

namespace App\Filament\Resources\BusNumbers\Tables;

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
                    ->label('Bus Number')
                    ->searchable(),

                TextColumn::make('bus_model')
                    ->label('Bus Model'),

                TextColumn::make('bus_type')
                    ->label('Bus Type'),

                TextColumn::make('seat_capacity')
                    ->label('Seat Capacity'),
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
