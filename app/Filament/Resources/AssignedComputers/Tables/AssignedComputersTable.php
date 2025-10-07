<?php

namespace App\Filament\Resources\AssignedComputers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssignedComputersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('system_unit_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('keyboard_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mouse_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('monitor_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ups_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assigned_to')
                    ->searchable(),
                TextColumn::make('assigned_date')
                    ->date()
                    ->sortable(),
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
