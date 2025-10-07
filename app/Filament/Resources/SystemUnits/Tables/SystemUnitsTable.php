<?php

namespace App\Filament\Resources\SystemUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SystemUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset_code')
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('date_aquired')
                    ->date()
                    ->sortable(),
                TextColumn::make('OS')
                    ->label('OS')
                    ->sortable(),
                TextColumn::make('windows_serial_number')
                    ->sortable(),
                TextColumn::make('microsoft_serial_number')
                    ->searchable(),
                TextColumn::make('ram')
                    ->sortable(),
                TextColumn::make('storage')
                    ->sortable(),
                TextColumn::make('processor')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
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
