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
                    ->toggleable()
                    ->label('Borrower')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department')
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('borrowed_date')
                    ->toggleable()
                    ->label('Date Borrowed')
                    ->date()
                    ->sortable(),

                TextColumn::make('department_head_name')
                    ->toggleable()
                    ->label('Dept. Head'),

                TextColumn::make('handled_by')
                    ->toggleable()
                    ->label('Handled By'),

                TextColumn::make('items_count')
                    ->toggleable()
                    ->label('Total Borrowed Itemss')
                    ->badge()
                    ->counts('items') //relationship-aware
                    ->colors(['primary']),

                TextColumn::make('return_status')
                    ->toggleable()
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
