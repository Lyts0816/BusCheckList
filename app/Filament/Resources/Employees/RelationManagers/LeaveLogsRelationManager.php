<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LeaveLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'leaveLogs';

    protected static ?string $title = 'Leave Records';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('leave_type')
                ->options([
                    'sick' => 'Sick Leave',
                    'vacation' => 'Vacation Leave',
                    'emergency' => 'Emergency Leave',
                    'maternity' => 'Maternity Leave',
                    'paternity' => 'Paternity Leave',
                    'other' => 'Other',
                ])
                ->required(),

            Forms\Components\TextInput::make('company')
                ->required()
                ->maxLength(255),

            Forms\Components\DatePicker::make('from_date')
                ->required(),

            Forms\Components\DatePicker::make('to_date')
                ->required(),

            Forms\Components\TextInput::make('relieved_by')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('conformed_by')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('approved_by')
                ->maxLength(255),

            Forms\Components\Textarea::make('reason')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('remarks')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_date')
                    ->label('From')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('to_date')
                    ->label('To')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company')
                    ->label('Company'),

                Tables\Columns\TextColumn::make('leave_type')
                    ->label('Leave Type')
                    ->badge(),

                Tables\Columns\TextColumn::make('relieved_by')
                    ->label('Relieved By'),

                Tables\Columns\TextColumn::make('conformed_by')
                    ->label('Conformed By'),

                Tables\Columns\TextColumn::make('approved_by')
                    ->label('Approved By'),
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
