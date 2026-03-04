<?php

namespace App\Filament\Resources\DispatchSheets\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispatchSheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dispatch_date')
                    ->toggleable()
                    ->date()
                    ->sortable(),

                TextColumn::make('route')
                    ->toggleable()
                    ->label('From - To')
                    ->getStateUsing(function ($record) {
                        $route = $record->route;

                        return $route ? ($route->from . ' - ' . $route->to) : 'No Route';
                    }),

                TextColumn::make('route.distance')
                    ->toggleable()
                    ->label('Distance')
                    ->sortable(),

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
