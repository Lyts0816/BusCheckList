<?php

namespace App\Filament\Resources\AssignedComputers\Tables;

use App\Models\AssignedComputer;
use App\Models\Departments;
use App\Models\Peripherals;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\Size;
use Illuminate\Database\Eloquent\Builder;

use Filament\Tables\Enums\RecordActionsPosition;

class AssignedComputersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn (AssignedComputer $record): string => \App\Filament\Resources\AssignedComputers\AssignedComputerResource::getUrl('view', [
                'record' => $record,
            ]))
            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('ID')
                    ->sortable(),
                    
                TextColumn::make('assigned_to')
                    ->label('Assigned To')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('assignment_type')
                    ->label('Assignment Type')
                    ->formatStateUsing(fn (?string $state): string => $state === 'individual' ? 'Individual Peripheral' : 'Computer Set')
                    ->badge()
                    ->searchable(),

                TextColumn::make('computer_name')
                    ->toggleable()
                    ->label('Computer Name')
                    ->searchable(),

                TextColumn::make('peripheral.serial_number')
                    ->label('Individual Peripheral')
                    ->formatStateUsing(fn ($state, $record): string => $record->peripheral
                        ? implode(' - ', array_filter([
                            $record->peripheral->item_type,
                            $record->peripheral->serial_number,
                            $record->peripheral->asset_code,
                        ]))
                        : 'N/A')
                    ->toggleable(),

                TextColumn::make('department_name')
                    ->label('Department')
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('systemUnit.serial_number')
                    ->toggleable()
                    ->label('System Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('keyboard.serial_number')
                    ->toggleable()
                    ->label('Keyboard')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mouse.serial_number')
                    ->toggleable()
                    ->label('Mouse')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('monitor.serial_number')
                    ->toggleable()
                    ->label('Monitor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ups.serial_number')
                    ->toggleable()
                    ->label('UPS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', direction: 'desc')
            ->reorderableColumns()

            ->filters([
                SelectFilter::make('assignment_type')
                    ->label('Assignment Type')
                    ->options([
                        'computer_set' => 'Computer Set',
                        'individual' => 'Individual Peripheral',
                    ])
                    ->searchable(),

                SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(fn (): array => AssignedComputer::query()
                        ->whereNotNull('assigned_to')
                        ->where('assigned_to', '!=', '')
                        ->distinct()
                        ->orderBy('assigned_to')
                        ->pluck('assigned_to', 'assigned_to')
                        ->toArray())
                    ->searchable(),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->searchable()
                    ->options(fn () => Departments::pluck('name', 'id')->toArray()),

                SelectFilter::make('computer_name')
                    ->label('Computer / Location')
                    ->options(fn (): array => AssignedComputer::query()
                        ->whereNotNull('computer_name')
                        ->where('computer_name', '!=', '')
                        ->distinct()
                        ->orderBy('computer_name')
                        ->pluck('computer_name', 'computer_name')
                        ->toArray())
                    ->searchable(),

                SelectFilter::make('individual_peripheral_type')
                    ->label('Individual Peripheral Type')
                    ->options(fn (): array => Peripherals::query()
                        ->whereIn('id', AssignedComputer::query()
                            ->where('assignment_type', 'individual')
                            ->whereNotNull('peripheral_id')
                            ->pluck('peripheral_id'))
                        ->whereNotNull('item_type')
                        ->distinct()
                        ->orderBy('item_type')
                        ->pluck('item_type', 'item_type')
                        ->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value): Builder => $query->whereHas(
                                'peripheral',
                                fn (Builder $peripheralQuery): Builder => $peripheralQuery->where('item_type', $value)
                            )
                        );
                    })
                    ->searchable(),

                SelectFilter::make('system_unit_id')
                    ->label('System Unit')
                    ->options(fn (): array => SystemUnit::query()
                        ->orderByDesc('id')
                        ->get()
                        ->mapWithKeys(fn (SystemUnit $unit): array => [
                            $unit->getKey() => self::assetLabel($unit),
                        ])
                        ->toArray())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('keyboard_id')
                    ->label('Keyboard')
                    ->options(fn (): array => self::peripheralOptions('Keyboard'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('mouse_id')
                    ->label('Mouse')
                    ->options(fn (): array => self::peripheralOptions('Mouse'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('monitor_id')
                    ->label('Monitor')
                    ->options(fn (): array => self::peripheralOptions('Monitor'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('ups_id')
                    ->label('UPS')
                    ->options(fn (): array => self::peripheralOptions('UPS'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('has_assets')
                    ->label('Asset Combination')
                    ->options([
                        'system_unit' => 'With System Unit',
                        'components' => 'With Computer Components',
                        'individual_peripheral' => 'Individual Peripheral',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'system_unit' => $query->whereNotNull('system_unit_id'),
                            'components' => $query->where(function (Builder $query): void {
                                $query->whereNotNull('keyboard_id')
                                    ->orWhereNotNull('mouse_id')
                                    ->orWhereNotNull('monitor_id')
                                    ->orWhereNotNull('ups_id');
                            }),
                            'individual_peripheral' => $query->where('assignment_type', 'individual'),
                            default => $query,
                        };
                    }),

            ], layout: FiltersLayout::Modal) ->filtersFormColumns(3)
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
                        ->tooltip('Edit assigned assets'),

                    Action::make('transfer')
                        ->label('Transfer Assignment')
                        ->icon('heroicon-o-arrow-right')
                        ->color('warning')
                        ->form([
                            TextInput::make('new_assigned_to')
                                ->label('New Assignee / Location')
                                ->required(),
                            Select::make('new_department_id')
                                ->label('New Department')
                                ->options(fn (): array => Departments::query()->orderBy('name')->pluck('name', 'id')->toArray())
                                ->searchable()
                                ->preload()
                                ->required(),
                            DateTimePicker::make('transferred_at')
                                ->label('Transfer Date')
                                ->default(now())
                                ->seconds(false)
                                ->required(),
                            TextInput::make('reason')
                                ->label('Reason')
                                ->maxLength(255)
                                ->required(),
                            Textarea::make('notes')
                                ->label('Notes')
                                ->rows(3),
                        ])
                        ->action(function (AssignedComputer $record, array $data): void {
                            DB::transaction(function () use ($record, $data): void {
                                $record->transferHistories()->create([
                                    'previous_assigned_to' => $record->assigned_to,
                                    'new_assigned_to' => $data['new_assigned_to'],
                                    'previous_department_id' => $record->department_id,
                                    'new_department_id' => $data['new_department_id'],
                                    'transferred_at' => $data['transferred_at'],
                                    'performed_by_id' => Auth::id(),
                                    'reason' => $data['reason'],
                                    'notes' => $data['notes'] ?? null,
                                ]);

                                $record->update([
                                    'assigned_to' => $data['new_assigned_to'],
                                    'department_id' => $data['new_department_id'],
                                ]);
                            });
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Assignment transferred')
                        ),
                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('top-start')
                    ->color('primary')

            ],position: RecordActionsPosition::BeforeCells)

            ->headerActions([
            \Filament\Actions\Action::make('export_csv')
                ->label('Export all record')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    // Get the current page URL with all query parameters
                    $currentUrl = request()->fullUrl();
                    $parsedUrl = parse_url($currentUrl);

                    // Parse query parameters
                    $queryParams = [];
                    if (isset($parsedUrl['query'])) {
                        parse_str($parsedUrl['query'], $queryParams);
                    }

                    // Build export URL with current filters
                    $exportUrl = route('export.assigned-computers');
                    $exportParams = [];

                    // Extract search parameter from tableSearch
                    if (isset($queryParams['tableSearch'])) {
                        $exportParams['search'] = $queryParams['tableSearch'];
                    }

                    // Extract other relevant filters
                    foreach ($queryParams as $key => $value) {
                        if (strpos($key, 'tableFilters') === 0 && !empty($value)) {
                            // Parse Filament filter format
                            if ($key === 'tableFilters[department][value]') {
                                $exportParams['department'] = $value;
                            }
                        }
                    }

                    // Build final URL
                    if (!empty($exportParams)) {
                        $exportUrl .= '?' . http_build_query($exportParams);
                    }

                    // Redirect to export URL
                    return redirect($exportUrl);
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.assigned-computers') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }

    private static function assetLabel(object $record): string
    {
        return implode(' - ', array_filter([
            $record->serial_number,
            $record->asset_code,
            $record->model,
        ])) ?: "Asset #{$record->getKey()}";
    }

    private static function peripheralOptions(string $itemType): array
    {
        return Peripherals::query()
            ->where('item_type', $itemType)
            ->orderByDesc('id')
            ->get()
            ->mapWithKeys(fn (Peripherals $peripheral): array => [
                $peripheral->getKey() => self::assetLabel($peripheral),
            ])
            ->toArray();
    }
}
