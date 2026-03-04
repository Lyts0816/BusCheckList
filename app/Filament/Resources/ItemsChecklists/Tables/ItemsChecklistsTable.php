<?php

namespace App\Filament\Resources\ItemsChecklists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsChecklistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->hidden(),

                TextColumn::make('item_type')
                    ->toggleable()
                    ->label('Item Type')
                    ->searchable(),

                TextColumn::make('item_model')
                    ->toggleable()
                    ->label('Item Model')
                    ->searchable(),

                TextColumn::make('bus.bus_number')
                    ->toggleable()
                    ->label('Bus Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('item_asset_code')
                    ->toggleable()
                    ->label('Asset Code')
                    ->searchable(),

                TextColumn::make('status')
                    ->toggleable()
                    ->label('Status')
                    ->searchable(),

                TextColumn::make('date_checked')
                    ->toggleable()
                    ->label('Date Checked')
                    ->date()
                    ->sortable(),

                TextColumn::make('remarks')
                    ->toggleable()
                    ->label('Remarks')
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
            ->defaultSort('id', direction: 'desc')
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
