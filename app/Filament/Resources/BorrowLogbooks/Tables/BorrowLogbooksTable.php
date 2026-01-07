<?php

namespace App\Filament\Resources\BorrowLogbooks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Enums\RecordActionsPosition;

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

                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'Returned' => 'success',
                        'Borrowed' => 'danger',
                    }),

                TextColumn::make('borrower_name')

                    ->searchable(),

                TextColumn::make('department')
                    ->searchable(),

                TextColumn::make('equipment')

                    ->searchable(),

                TextColumn::make('date_returned')
                    ->date()
                    ->sortable(),

                TextColumn::make('item_asset_code')

                    ->searchable(),

                TextColumn::make('department_head_name')

                    ->searchable(),

                TextColumn::make('purpose_borrowing'),

                TextColumn::make('handled_by')
                    ->searchable(),

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
                \Filament\Tables\Filters\SelectFilter::make('department')
                    ->options(fn() => \App\Models\BorrowLogbook::query()
                        ->distinct()
                        ->pluck('department', 'department')
                        ->toArray()
                    ),
                \Filament\Tables\Filters\SelectFilter::make('borrower_name')
                    ->options(fn() => \App\Models\BorrowLogbook::query()
                        ->distinct()
                        ->pluck('borrower_name', 'borrower_name')
                        ->toArray()
                    ),
                
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
