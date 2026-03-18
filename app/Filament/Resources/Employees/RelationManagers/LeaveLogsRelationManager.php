<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use App\Models\LeaveLog;
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
            Forms\Components\TextInput::make('control_number')
                ->label('Control Number')
                ->default(fn(): string => LeaveLog::generateNextControlNumber())
                ->readOnly()
                ->dehydrated()
                ->unique(ignoreRecord: true)
                ->required()
                ->maxLength(255),

            Forms\Components\DatePicker::make('date_filed')
                ->label('Date Filed')
                ->default(now())
                ->required(),

            Forms\Components\Select::make('leave_type')
                ->options([
                    'Sick Leave' => 'Sick Leave',
                    'Vacation Leave' => 'Vacation Leave',
                    'Emergency Leave' => 'Emergency Leave',
                    'Maternity Leave' => 'Maternity Leave',
                    'Paternity Leave' => 'Paternity Leave',
                    'Other' => 'Other',
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

            Forms\Components\TextInput::make('conformed_by_position')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('approved_by')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('approved_by_position')
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
                Tables\Columns\TextColumn::make('control_number')
                    ->label('Control Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_filed')
                    ->label('Date Filed')
                    ->date()
                    ->sortable(),

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

                Tables\Columns\TextColumn::make('conformed_by_position')
                    ->label('Conformed By Position'),

                Tables\Columns\TextColumn::make('approved_by')
                    ->label('Approved By'),

                Tables\Columns\TextColumn::make('approved_by_position')
                    ->label('Approved By Position'),
            ])
            ->headerActions([
                CreateAction::make()
                ->modalWidth('7xl'),
            ])
            ->recordActions([
                EditAction::make()
                ->modalWidth('7xl'),
                DeleteAction::make(),
            ]);
    }
}
