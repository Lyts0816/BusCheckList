<?php

namespace App\Filament\Resources\BorrowLogbooks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BorrowLogbooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('borrow_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('borrower_name')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('equipment')
                    ->searchable(),
                TextColumn::make('item_asset_code')
                    ->searchable(),
                TextColumn::make('department_head_name')
                    ->searchable(),
                TextColumn::make('purpose_borrowing'),
                TextColumn::make('handled_by')
                    ->searchable(),
                TextColumn::make('date_returned')
                    ->date()
                    ->sortable(),
                TextColumn::make('remarks'),
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
