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
                    ->label('Item Name')
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category'),

                TextColumn::make('unit')
                    ->label('Unit of Measurement'),

                TextColumn::make('stock')
                    ->label('Stock Quantity')
                    ->badge()
                    ->color(fn (int $state) => $state <= 3 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('created_at')
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
