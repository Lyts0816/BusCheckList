<?php

namespace App\Filament\Resources\OfficeSupplies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OfficeSuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->toggleable()
                    ->label('Item Name')
                    ->sortable(),

                TextColumn::make('category')
                    ->toggleable()
                    ->label('Category'),

                TextColumn::make('unit')
                    ->toggleable()
                    ->label('Unit of Measurement'),

                TextColumn::make('stock')
                    ->toggleable()
                    ->label('Stock Quantity')
                    ->badge()
                    ->color(fn (int $state) => $state <= 3 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->toggleable()
                    ->label('Created At')
                    ->dateTime(),
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
