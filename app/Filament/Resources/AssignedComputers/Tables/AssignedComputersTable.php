<?php

namespace App\Filament\Resources\AssignedComputers\Tables;

use App\Models\AssignedComputer;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;

class AssignedComputersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('assigned_to')
                    ->searchable(),
                TextColumn::make('department')
                    ->sortable(),
                TextColumn::make('systemUnit.serial_number')
                    ->label('System Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('keyboard.serial_number')
                    ->label('Keyboard')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mouse.serial_number')
                    ->label('Mouse')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('monitor.serial_number')
                    ->label('Monitor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ups.serial_number')
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
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
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
}
