<?php

namespace App\Filament\Resources\Routes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoutesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('to')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('distance')
                    ->toggleable()
                    ->label('Distance (in km)')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('remarks')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->toggleable()
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->toggleable()
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                
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
