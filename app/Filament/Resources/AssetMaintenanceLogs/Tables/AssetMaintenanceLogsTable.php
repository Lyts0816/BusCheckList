<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Tables;

use App\Filament\Resources\AssetMaintenanceLogs\Schemas\AssetMaintenanceLogForm;
use App\Filament\Resources\AssetMaintenanceLogs\Schemas\AssetMaintenanceLogInfolist;
use App\Models\AssignedComputer;
use App\Models\Peripherals;
use App\Models\Printer;
use App\Models\SystemUnit;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Support\Enums\Size;
use Illuminate\Database\Eloquent\Builder;

class AssetMaintenanceLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('maintainable_type')
                    ->label('Asset Type')
                    ->formatStateUsing(function (?string $state): ?string {
                        if (! $state) {
                            return null;
                        }

                        $baseName = class_basename($state);

                        return trim(preg_replace('/(?<!^)([A-Z])/', ' $1', $baseName));
                    }),

                TextColumn::make('maintainable_id')
                    ->label('Asset')
                    ->getStateUsing(fn ($record) => data_get($record, 'maintainable.asset_code')
                        ?? data_get($record, 'maintainable.name')
                        ?? $record->maintainable_id)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('component.name')
                    ->label('Component')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('maintenance_type')
                    ->badge(),

                TextColumn::make('maintenance_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('performed_by')
                    ->searchable(),

                TextColumn::make('cost')                    
                    ->money('php')
                    ->sortable(),

                // TextColumn::make('next_maintenance')
                //     ->date()
                //     ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', direction: 'desc')

            ->filters([
                SelectFilter::make('department')
                    ->label('Department')
                    ->options(function (): array {
                        $assignedComputerDepartments = AssignedComputer::query()
                            ->whereNotNull('department')
                            ->where('department', '!=', '')
                            ->distinct()
                            ->pluck('department')
                            ->toArray();

                        $printerDepartments = Printer::query()
                            ->whereNotNull('department')
                            ->where('department', '!=', '')
                            ->distinct()
                            ->pluck('department')
                            ->toArray();

                        $departments = array_values(array_unique(array_merge($assignedComputerDepartments, $printerDepartments)));
                        sort($departments);

                        return array_combine($departments, $departments);
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $department = $data['value'] ?? null;

                        if (! $department) {
                            return $query;
                        }

                        return $query->where(function (Builder $departmentQuery) use ($department): void {
                            $departmentQuery
                                ->whereHasMorph('maintainable', [SystemUnit::class], function (Builder $morphQuery) use ($department): void {
                                    $morphQuery->whereHas('assignedComputer', function (Builder $assignedQuery) use ($department): void {
                                        $assignedQuery->where('department', $department);
                                    });
                                })
                                ->orWhereHasMorph('maintainable', [Peripherals::class], function (Builder $morphQuery) use ($department): void {
                                    $morphQuery->where(function (Builder $peripheralQuery) use ($department): void {
                                        $peripheralQuery
                                            ->whereHas('assignedKeyboards', fn (Builder $assignedQuery): Builder => $assignedQuery->where('department', $department))
                                            ->orWhereHas('assignedMice', fn (Builder $assignedQuery): Builder => $assignedQuery->where('department', $department))
                                            ->orWhereHas('assignedMonitors', fn (Builder $assignedQuery): Builder => $assignedQuery->where('department', $department))
                                            ->orWhereHas('assignedUps', fn (Builder $assignedQuery): Builder => $assignedQuery->where('department', $department));
                                    });
                                })
                                ->orWhereHasMorph('maintainable', [Printer::class], fn (Builder $morphQuery): Builder => $morphQuery->where('department', $department));
                        });
                    }),
            ])

            ->headerActions([
                Action::make('export_csv')
                    ->label('Export all records')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $currentUrl = request()->fullUrl();
                        $parsedUrl = parse_url($currentUrl);

                        $queryParams = [];
                        if (isset($parsedUrl['query'])) {
                            parse_str($parsedUrl['query'], $queryParams);
                        }

                        $exportUrl = route('export.asset-maintenance-logs');
                        $exportParams = [];

                        if (isset($queryParams['tableSearch'])) {
                            $exportParams['search'] = $queryParams['tableSearch'];
                        }

                        if (! empty($exportParams)) {
                            $exportUrl .= '?' . http_build_query($exportParams);
                        }

                        return redirect($exportUrl);
                    }),
            ])

            ->recordActions([
                ActionGroup::make([

                    ViewAction::make()
                        ->schema(fn (Schema $schema): Schema => AssetMaintenanceLogInfolist::configure($schema))
                        ->modalWidth('7xl'),
                        
                    EditAction::make()
                        ->schema(fn (Schema $schema): Schema => AssetMaintenanceLogForm::configure($schema)),

                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('primary'),
                ],position: RecordActionsPosition::BeforeCells)
     

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.asset-maintenance-logs') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),
                ]),
            ]);
    }
}
