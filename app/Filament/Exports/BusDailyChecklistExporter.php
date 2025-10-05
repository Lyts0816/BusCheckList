<?php

namespace App\Filament\Exports;

use App\Models\BusDailyChecklist;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Writer;

class BusDailyChecklistExporter extends Exporter
{
    protected static ?string $model = BusDailyChecklist::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('bus.bus_number')
                ->label('Bus Number'),
            ExportColumn::make('check_date'),
            ExportColumn::make('checked')
                ->label('Checked')
                ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
            ExportColumn::make('remarks'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
        ->setFontBold()
        ->setFontSize(12)
        ->setFontName('Arial Black');
    }

    public function configureXlsxWriterBeforeClose(Writer $writer): Writer
    {
        $sheetView = new SheetView();
        $sheetView->setFreezeRow(2);
        $sheetView->setFreezeColumn('B');
        
        $sheet = $writer->getCurrentSheet();
        $sheet->setSheetView($sheetView);
        $sheet->setName('export');
        
        return $writer;
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your bus daily checklist export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
