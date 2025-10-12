<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peripherals;

class PeripheralsExport extends Controller
{
    public function exportPeripherals(Request $request)
    {
        // Start with base query for peripherals
        $query = Peripherals::query();

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply search filters if provided
            if ($request->filled('search')) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('item_type', 'like', "%{$searchTerm}%")
                        ->orWhere('asset_code', 'like', "%{$searchTerm}%")
                        ->orWhere('serial_number', 'like', "%{$searchTerm}%")
                        ->orWhere('model', 'like', "%{$searchTerm}%")
                        ->orWhere('date_acquired', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Apply sorting if provided
            if ($request->filled('sort')) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'asc');

                // Whitelist sortable fields to avoid invalid columns
                $sortable = [
                    'id',
                    'item_type',
                    'asset_code',
                    'serial_number',
                    'model',
                    'date_acquired',
                    'description',
                    'created_at',
                    'updated_at',
                ];
                if (in_array($sortField, $sortable, true)) {
                    $query->orderBy($sortField, $sortDirection);
                }
            }
        }

        // Get the filtered results
        $peripherals = $query->get();

        // Create CSV content
        $csvContent = $this->generatePeripheralsCSVFormat($peripherals);

        // Generate filename with timestamp and filter info
        $filename = 'peripherals_' . date('Y-m-d_H-i-s');
        if ($request->has('search')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        // Return CSV as download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // FUNCTION FOR CSV
    private function generatePeripheralsCSVFormat($peripherals)
    {
        // Define CSV headers for peripherals export
        $headers = [
            'ID',
            'Item Type',
            'Asset Code',
            'Serial Number',
            'Model',
            'Date Acquired',
            'Description',
            'Created At',
            'Updated At',
        ];

        // Start CSV content with headers
        $csv = '"' . implode('","', $headers) . '"' . "\n";

        // Add data rows
        foreach ($peripherals as $item) {
            $row = [
                $item->id,
                $item->item_type ?? 'N/A',
                $item->asset_code ?? 'N/A',
                $item->serial_number ?? 'N/A',
                $item->model ?? 'N/A',
                $item->date_acquired ?? 'N/A',
                $item->description ?? 'N/A',
                $item->created_at,
                $item->updated_at,
            ];

            // Escape each value for CSV safety (quotes, commas, etc.)
            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    private function escapeCsvValue($value)
    {
        return str_replace('"', '""', (string) $value); // Escape double quotes
    }
}
