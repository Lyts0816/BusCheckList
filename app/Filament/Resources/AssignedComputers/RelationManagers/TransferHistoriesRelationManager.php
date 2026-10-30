<?php

namespace App\Filament\Resources\AssignedComputers\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransferHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'transferHistories';

    protected static ?string $title = 'Transfer History';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('previous_assigned_to')
                    ->label('Previous Assignee / Location')
                    ->searchable(),
                TextColumn::make('new_assigned_to')
                    ->label('New Assignee / Location')
                    ->searchable(),
                TextColumn::make('previousDepartment.name')
                    ->label('Previous Department')
                    ->searchable(),
                TextColumn::make('newDepartment.name')
                    ->label('New Department')
                    ->searchable(),
                TextColumn::make('transferred_at')
                    ->label('Transferred At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('performedBy.name')
                    ->label('Performed By')
                    ->searchable(),
                TextColumn::make('reason')
                    ->searchable(),
                TextColumn::make('notes')
                    ->wrap(),
            ])
            ->defaultSort('transferred_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
