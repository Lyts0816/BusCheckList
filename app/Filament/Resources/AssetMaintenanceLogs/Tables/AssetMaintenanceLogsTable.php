<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Tables;

use App\Filament\Resources\AssetMaintenanceLogs\Schemas\AssetMaintenanceLogForm;
use App\Filament\Resources\AssetMaintenanceLogs\Schemas\AssetMaintenanceLogInfolist;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Support\Enums\Size;

class AssetMaintenanceLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

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

                TextColumn::make('days_since_maintenance')
                    ->label('Since Last Maintenance')
                    ->getStateUsing(function ($record): ?string {
                        if (! $record->maintenance_date) {
                            return null;
                        }

                        $maintenanceDate = Carbon::parse($record->maintenance_date)->startOfDay();
                        $today = now()->startOfDay();

                        if ($maintenanceDate->greaterThan($today)) {
                            return 'Scheduled in ' . $today->diffInDays($maintenanceDate) . ' days';
                        }

                        $totalDays = $maintenanceDate->diffInDays($today);
                        $parts = $maintenanceDate->diff($today);

                        $segments = [];

                        if ($parts->y > 0) {
                            $segments[] = $parts->y . ' year' . ($parts->y > 1 ? 's' : '');
                        }

                        if ($parts->m > 0) {
                            $segments[] = $parts->m . ' month' . ($parts->m > 1 ? 's' : '');
                        }

                        if ($parts->d > 0 || empty($segments)) {
                            $segments[] = $parts->d . ' day' . ($parts->d > 1 ? 's' : '');
                        }

                        return $totalDays . ' days (' . implode(', ', $segments) . ')';
                    })
                    ->badge()
                    ->color(function ($record): string {
                        if (! $record->maintenance_date) {
                            return 'gray';
                        }

                        $days = Carbon::parse($record->maintenance_date)->startOfDay()->diffInDays(now()->startOfDay(), false);

                        if ($days >= 365) {
                            return 'danger';
                        }

                        if ($days >= 180) {
                            return 'warning';
                        }

                        return 'success';
                    }),

                TextColumn::make('performed_by')
                    ->searchable(),

                TextColumn::make('cost')                    
                    ->money('php')
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
            ])->defaultSort('id', direction: 'desc')

            ->filters([
                //
            ])

            ->recordActions([
                ActionGroup::make([

                    ViewAction::make()
                        ->schema(fn (Schema $schema): Schema => AssetMaintenanceLogInfolist::configure($schema))
                        ->modalWidth('7xl'),
                        
                    EditAction::make()
                        ->schema(fn (Schema $schema): Schema => AssetMaintenanceLogForm::configure($schema)),

                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('primary'),
                ],position: RecordActionsPosition::BeforeCells)
     

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
