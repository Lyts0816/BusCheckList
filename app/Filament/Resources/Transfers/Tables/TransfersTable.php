<?php

namespace App\Filament\Resources\Transfers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;

class TransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->toggleable()
                    ->label('Date Transferred')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Transferred',
                        'danger' => 'Cancelled',
                    ]),

                TextColumn::make('to')
                    ->toggleable()
                    ->label('To')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('items_count')
                    ->toggleable()
                    ->label('Total Transferred Items')
                    ->badge()
                    ->counts('items') //relationship-aware
                    ->colors(['primary']),
            ])->defaultSort('id', direction: 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('7xl'),
                    
                EditAction::make()
                    ->modalWidth('7xl'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
