<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetMaintenanceLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('maintainable_type')
                    ->label('Asset Type')
                    ->formatStateUsing(function (?string $state): ?string {
                        if (! $state) {
                            return null;
                        }

                        $baseName = class_basename($state);

                        return trim(preg_replace('/(?<!^)([A-Z])/', ' $1', $baseName));
                    }),

                TextColumn::make('maintainable_id')
                    ->label('Asset')
                    ->getStateUsing(fn ($record) => data_get($record, 'maintainable.asset_code')
                        ?? data_get($record, 'maintainable.name')
                        ?? $record->maintainable_id)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('component.name')
                    ->label('Component')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('maintenance_type')
                    ->badge(),

                TextColumn::make('maintenance_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('performed_by')
                    ->searchable(),

                TextColumn::make('cost')
                    ->money()
                    ->sortable(),

                // TextColumn::make('next_maintenance')
                //     ->date()
                //     ->sortable(),

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
