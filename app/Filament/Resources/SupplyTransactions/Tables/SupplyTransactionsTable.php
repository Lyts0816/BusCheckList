<?php

namespace App\Filament\Resources\SupplyTransactions\Tables;

use App\Models\SupplyTransaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplyTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['items.supply']))
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state) => $state === 'IN' ? 'success' : 'danger'),

                // TextColumn::make('department')
                //     ->searchable(),

                TextColumn::make('user')
                    ->label('Recipient Name')
                    ->searchable(),

                TextColumn::make('items')
                    ->label('Items')
                    ->getStateUsing(function (SupplyTransaction $record): string {
                        return $record->items
                            ->map(function ($item): string {
                                $name = $item->supply?->name ?? 'Unknown';

                                return $name . ' x' . $item->quantity;
                            })
                            ->implode(', ');
                    })
                    ->wrap(),

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
