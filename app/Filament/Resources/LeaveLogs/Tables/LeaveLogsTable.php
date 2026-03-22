<?php

namespace App\Filament\Resources\LeaveLogs\Tables;

use App\Filament\Pages\LeaveDashboard;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Support\Enums\Size;

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
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth('7xl'),

                    EditAction::make()
                        ->modalWidth('7xl')
                        ->visible(fn ($livewire): bool => ! $livewire instanceof LeaveDashboard),

                    Action::make('print')
                        ->label('Print')
                        ->color('info')
                        ->icon('heroicon-o-printer')
                        ->url(fn ($record) => route('export.leave-logs.print', ['id' => $record->id]))
                        ->openUrlInNewTab(),
                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('primary'),

            ],position: RecordActionsPosition::BeforeCells)
            
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->hasSuperAdminLeaveModule() ?? false),
                ]),
            ]);
    }
}
