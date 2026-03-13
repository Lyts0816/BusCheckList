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
                    'repair' => 'Repair',
                    'upgrade' => 'Upgrade',
                    'replacement' => 'Replacement',
                ])
                ->required(),

            Forms\Components\DatePicker::make('maintenance_date')
                ->required(),

            Forms\Components\TextInput::make('performed_by')
                ->maxLength(255),

            Forms\Components\Textarea::make('issue_reported')
                ->label('Issue Reported')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('action_taken')
                ->label('Action Taken')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('cost')
                ->numeric()
                ->prefix('PHP '),

            Forms\Components\DatePicker::make('next_maintenance'),

            Forms\Components\Textarea::make('remarks')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('component.name')
                    ->label('Component'),

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