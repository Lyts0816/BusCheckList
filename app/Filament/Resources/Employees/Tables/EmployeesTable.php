<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('employee_code')
                    ->label('Employee Code')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department')
                    ->searchable()
                    ->toggleable(),

                // TextColumn::make('remaining_vl')
                //     ->label('Remaining VL')
                //     ->searchable()
                //     ->toggleable(),

                // TextColumn::make('remaining_sl')
                //     ->label('Remaining SL')
                //     ->searchable()
                //     ->toggleable(),

                // TextColumn::make('availed_vl')
                //     ->label('Availed VL')
                //     ->searchable()
                //     ->toggleable(),

                // TextColumn::make('availed_sl')
                //     ->label('Availed SL')
                //     ->searchable()
                //     ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', direction: 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([

                    ViewAction::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->icon('heroicon-o-eye')
                        ->tooltip('View details'),

                    EditAction::make()
                        ->color('primary')
                        ->hiddenLabel()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit record'),

                    Action::make('leave')
                        ->label('Leave Records')
                        ->color('warning')
                        ->hiddenLabel()
                        ->icon('heroicon-o-calendar-days')
                        ->tooltip('Leave records')
                        ->url(fn (Employee $record): string => EmployeeResource::getUrl('edit', [
                            'record' => $record,
                            'relation' => 'leave',
                        ])),

                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('primary'),

            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
