<?php

namespace App\Filament\Resources\LeaveLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('control_number')
                    ->label('Control Number')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('leave_type')
                    ->label('Leave Type')
                    ->badge(),

                TextColumn::make('company')
                    ->searchable(),

                TextColumn::make('from_date')
                    ->label('From')
                    ->date()
                    ->sortable(),

                TextColumn::make('to_date')
                    ->label('To')
                    ->date()
                    ->sortable(),

                TextColumn::make('relieved_by')
                    ->label('Relieved By')
                    ->searchable(),

                TextColumn::make('conformed_by')
                    ->label('Conformed By')
                    ->searchable(),

                TextColumn::make('approved_by')
                    ->label('Approved By')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
