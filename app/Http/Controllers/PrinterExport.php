<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use Illuminate\Support\Collection;

class PrinterExport extends Controller
{
    public function exportAssignedPrinters(Request $request)
    {
        // Start with base query for printers
        $query = Printer::query()->with('department');

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids, 'and', false);
        } else {
            if ($request->filled('department_id') || $request->filled('department')) {
                $departmentId = $request->input('department_id', $request->input('department'));
                $query->where('department_id', $departmentId);
            }

            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('printer_host', 'like', "%{$searchTerm}%")
                        ->orWhereHas('department', function ($dq) use ($searchTerm) {
                            $dq->where('name', 'like', "%{$searchTerm}%");
                        })
                        ->orWhere('printer_model', 'like', "%{$searchTerm}%")
                        ->orWhere('printer_asset_code', 'like', "%{$searchTerm}%")
                        ->orWhere('printer_serial_number', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Apply sorting if provided
            if ($request->has('sort') && !empty($request->sort)) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'asc');
                $query->orderBy($sortField, $sortDirection);
            }
        }

        // Get the filtered results
        $printers = $query->get();

        // Create CSV content
        $csvContent = $this->generatePrinterCSVFormat($printers);

        // Generate filename with timestamp and filter info
        $filename = 'assigned_printers_' . date('Y-m-d_H-i-s');
        if ($request->has('search')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        // Return CSV as download
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // FUNCTION FOR CSV
    private function generatePrinterCSVFormat(Collection $printers): string
    {
        $title = 'PRINTER INVENTORY - ' . now()->format('F d, Y');
        // Define CSV headers for printer export
        $headers = [
            'ID',
            'Printer Host',
            'Status',
            'Department',
            'Printer Model',
            'Printer Asset Code',
            'Printer Serial Number',
            'Date Acquired',
            'Description',
            'Created At',
            'Updated At'
        ];

        // Start CSV content with headers
        $csv  = '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";

        // Add data rows
        foreach ($printers as $printer) {
            $row = [
                $printer->id,
                $printer->printer_host ?? 'N/A',
                $printer->status ?? 'N/A',
                $printer->department?->name ?? 'N/A',
                $printer->printer_model ?? 'N/A',
                $printer->printer_asset_code ?? 'N/A',
                $printer->printer_serial_number ?? 'N/A',
                $printer->date_aquired ?? 'N/A',
                $printer->description ?? 'N/A',
                $printer->created_at,
                $printer->updated_at,
            ];

            // Escape each value for CSV safety (quotes, commas, etc.)
            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    private function escapeCsvValue(mixed $value): string
    {
        $value = trim((string) $value);

        if (
            $value !== '' &&
            preg_match('/^\d+$/', $value) &&
            (strlen($value) >= 12 || (strlen($value) > 1 && str_starts_with($value, '0')))
        ) {
            $value = '="' . $value . '"';
        }

        return str_replace('"', '""', $value);
    }
}
