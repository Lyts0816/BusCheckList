<?php

namespace App\Filament\Resources\BorrowLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BorrowLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('borrower_name')
                    ->label('Borrower')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department')
                    ->sortable(),

                TextColumn::make('borrowed_date')
                    ->label('Date Borrowed')
                    ->date()
                    ->sortable(),

                TextColumn::make('department_head_name')
                    ->label('Dept. Head'),

                TextColumn::make('handled_by'),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->badge()
                    ->counts('items') //relationship-aware
                    ->colors(['primary']),

                TextColumn::make('return_status')
                    ->label('Return Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if ($record->not_returned_count === 0) {
                            return 'All Returned';
                        }

                        return $record->not_returned_count . ' Not Returned';
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'All Returned',
                        'danger' => fn($state) => str_contains($state, 'Not Returned'),
                    ]),





            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
