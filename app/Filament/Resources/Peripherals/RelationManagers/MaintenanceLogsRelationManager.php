<?php

namespace App\Filament\Resources\Peripherals\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use App\Models\OfficeSupplies;

class MaintenanceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenanceLogs';

    protected static ?string $title = 'Maintenance History';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('maintenance_type')
                ->options([
                    'preventive' => 'Preventive',
                    'cleaning' => 'Cleaning',
                    'repair' => 'Repair',
                    'upgrade' => 'Upgrade',
                    'replacement' => 'Replacement',
                ])
                ->required()
                ->live()
                ->columnSpan(1),

            Forms\Components\Select::make('component_id')
                ->label('Component')
                ->options(fn() => \App\Models\Component::query()
                    ->where('asset_type', 'peripheral')
                    ->pluck('name', 'id')
                    ->toArray())
                ->searchable()
                ->preload()
                ->required()
                ->columnSpan(1),

            Forms\Components\Select::make('office_supply_id')
                ->label('Replacement Item')
                ->options(function (): array {
                    return OfficeSupplies::query()
                        ->orderBy('name', 'asc')
                        ->get()
                        ->mapWithKeys(function ($supply) {
                            $baseName = $supply->name ?: 'Supply #' . $supply->id;

                            $label = $supply->brand
                                ? $baseName . ' (' . $supply->brand . ')'
                                : $baseName;

                            return [$supply->id => $label];
                        })
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->visible(fn(callable $get): bool => $get('maintenance_type') === 'replacement')
                ->columnSpan(1),

            Forms\Components\DatePicker::make('maintenance_date')
                ->required()
                ->columnSpan(1),



            Forms\Components\TextInput::make('performed_by')
                ->maxLength(255)
                ->columnSpan(1),

            Forms\Components\TextInput::make('cost')
                ->numeric()
                ->prefix('₱')
                ->columnSpan(1),

            Forms\Components\Textarea::make('issue_reported')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('action_taken')
                ->columnSpanFull(),

            // Forms\Components\DatePicker::make('next_maintenance'),

            Forms\Components\Textarea::make('remarks')
                ->columnSpanFull(),
        ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('days_since_maintenance')
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

                Tables\Columns\TextColumn::make('component.name')
                    ->label('Component'),

                Tables\Columns\TextColumn::make('officeSupply.name')
                    ->label('Replacement Item')
                    ->getStateUsing(function ($record) {
                        if (! $record->officeSupply) {
                            return 'N/A';
                        }

                        return $record->officeSupply->brand
                            ? $record->officeSupply->name . ' (' . $record->officeSupply->brand . ')'
                            : $record->officeSupply->name;
                    }),

                Tables\Columns\TextColumn::make('maintenance_type')
                    ->badge(),

                Tables\Columns\TextColumn::make('performed_by')
                    ->label('Performed By'),

                Tables\Columns\TextColumn::make('cost')
                    ->money('PHP')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
