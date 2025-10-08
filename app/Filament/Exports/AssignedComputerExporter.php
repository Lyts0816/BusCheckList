<?php

namespace App\Filament\Exports;

use App\Models\AssignedComputer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class AssignedComputerExporter extends Exporter
{
    protected static ?string $model = AssignedComputer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('assigned_to')->label('Assigned To'),
            ExportColumn::make('department')->label('Department'),
            ExportColumn::make('system_unit_id')->label('System Unit ID'),
            ExportColumn::make('asset_code')
                ->label('System Unit Asset Code')
                ->state(function (AssignedComputer $record): string {
                    return $record->systemUnit?->asset_code ?? 'N/A';
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your assigned computer export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
