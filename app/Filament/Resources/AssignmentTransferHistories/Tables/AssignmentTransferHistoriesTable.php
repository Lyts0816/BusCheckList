<?php

namespace App\Filament\Resources\AssignmentTransferHistories\Tables;

use App\Models\AssignedComputer;
use App\Models\Departments;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\FiltersLayout;

class AssignmentTransferHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('assignment.id')
                    ->label('Assignment ID')
                    ->sortable(),
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
                    ->searchable()
                    ->wrap(),
            ])
            ->defaultSort('transferred_at', 'desc')
            ->filters([
                SelectFilter::make('assigned_computer_id')
                    ->label('Assignment')
                    ->options(fn (): array => AssignedComputer::query()
                        ->orderByDesc('id')
                        ->get()
                        ->mapWithKeys(fn (AssignedComputer $assignment): array => [
                            $assignment->getKey() => sprintf(
                                '#%d - %s (%s)',
                                $assignment->getKey(),
                                $assignment->assigned_to,
                                $assignment->computer_name
                            ),
                        ])
                        ->toArray())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('assignment_type')
                    ->label('Assignment Type')
                    ->options([
                        'computer_set' => 'Computer Set',
                        'individual' => 'Individual Peripheral',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, $value): Builder => $query->whereHas(
                            'assignment',
                            fn (Builder $assignmentQuery): Builder => $assignmentQuery->where('assignment_type', $value)
                        )
                    ))
                    ->searchable(),

                SelectFilter::make('previous_assigned_to')
                    ->label('Previous Assignee / Location')
                    ->options(fn (): array => self::distinctValues('previous_assigned_to'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('new_assigned_to')
                    ->label('New Assignee / Location')
                    ->options(fn (): array => self::distinctValues('new_assigned_to'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('previous_department_id')
                    ->label('Previous Department')
                    ->options(fn (): array => Departments::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('new_department_id')
                    ->label('New Department')
                    ->options(fn (): array => Departments::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('performed_by_id')
                    ->label('Performed By')
                    ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('reason')
                    ->label('Reason')
                    ->options(fn (): array => self::distinctValues('reason', true))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('asset_type')
                    ->label('Assigned Asset Type')
                    ->options([
                        'system_unit' => 'System Unit',
                        'keyboard' => 'Keyboard',
                        'mouse' => 'Mouse',
                        'monitor' => 'Monitor',
                        'ups' => 'UPS',
                        'individual_peripheral' => 'Individual Peripheral',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! $value) {
                            return $query;
                        }

                        if ($value === 'individual_peripheral') {
                            return $query->whereHas('assignment', fn (Builder $assignmentQuery): Builder => $assignmentQuery->where('assignment_type', 'individual'));
                        }

                        return $query->whereHas('assignment', fn (Builder $assignmentQuery): Builder => $assignmentQuery->whereNotNull($value . '_id'));
                    }),
            ], layout: FiltersLayout::Modal) ->filtersFormColumns(3);
    }

    private static function distinctValues(string $column, bool $excludeEmpty = false): array
    {
        $query = \App\Models\AssignmentTransferHistory::query()
            ->whereNotNull($column)
            ->distinct()
            ->orderBy($column);

        if ($excludeEmpty) {
            $query->where($column, '!=', '');
        }

        return $query->pluck($column, $column)->toArray();
    }
}
