<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemUnit;
use Carbon\Carbon;

class SystemUnitExport extends Controller
{
        public function exportAssignedSystemUnits(Request $request)
    {
        // Start with base query for system units
        $query = SystemUnit::with('assignedComputer'); // Eager load relationship

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            // Add LEFT JOIN first, then filter by system_units.id
            $query->leftJoin('assigned_computers', 'system_units.id', '=', 'assigned_computers.system_unit_id')
                  ->select('system_units.*')
                  ->whereIn('system_units.id', $ids) // Specify table name to avoid ambiguity
                  ->orderBy('assigned_computers.department', 'asc')
                  ->orderBy('system_units.id', 'desc');
        } else {
            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('asset_code', 'like', "%{$searchTerm}%")
                        ->orWhere('model', 'like', "%{$searchTerm}%")
                        ->orWhere('serial_number', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%")
                        ->orWhere('OS', 'like', "%{$searchTerm}%");
                });
            }

            // Apply sorting if provided
            if ($request->has('sort') && !empty($request->sort)) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'asc');
                $query->orderBy($sortField, $sortDirection);
            }

            // Order by department (join with assigned_computers table)
            $query->leftJoin('assigned_computers', 'system_units.id', '=', 'assigned_computers.system_unit_id')
                  ->select('system_units.*')
                  ->orderBy('assigned_computers.department', 'asc')
                  ->orderBy('system_units.id', 'desc');
        }

        // Get the filtered results
        $systemUnits = $query->get();

        // Create CSV content
        $csvContent = $this->generateSystemUnitCSVFormat($systemUnits);

        // Generate filename with timestamp and filter info
        $filename = 'assigned_system_units_' . date('Y-m-d_H-i-s');
        if ($request->has('search')) {
            $filename .= '_filtered';
        }
        if ($request->has('ids')) {
            $filename .= '_selected';
        }
        $filename .= '.csv';

        // Return CSV as download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // FUNCTION FOR CSV
    private function generateSystemUnitCSVFormat($systemUnits)
    {
        // Define CSV headers for system unit export
        $headers = [
            'ID',
            'Assigned To',
            'Department',
            'Asset Code',
            'Serial Number',
            'Model',
            'Date Acquired',
            'Years in Service',
            'Operating System',
            'Windows Serial Number',
            'Microsoft Serial Number',
            'RAM',
            'Storage',
            'Processor',
            'IP Address',
            'Description',
            'Created At',
            'Updated At',
        ];

        // Start CSV content with headers
        $csv = '"' . implode('","', $headers) . '"' . "\n";

        // Add data rows
        foreach ($systemUnits as $unit) {
            // Calculate years in service
            $yearsInService = 'N/A';
            if ($unit->date_aquired) {
                $diff = Carbon::parse($unit->date_aquired)->diff(now());
                $years = $diff->y;
                $months = $diff->m;
                $yearLabel = $years === 1 ? 'year' : 'years';
                $monthLabel = $months === 1 ? 'month' : 'months';
                $yearsInService = "{$years} {$yearLabel}, {$months} {$monthLabel}";
            }

            $row = [
                $unit->id,
                $unit->assignedComputer?->assigned_to ?? 'Unassigned',
                $unit->assignedComputer?->department ?? 'N/A',
                $unit->asset_code ?? 'N/A',
                $unit->serial_number ?? 'N/A',
                $unit->model ?? 'N/A',
                $unit->date_aquired ?? 'N/A',
                $yearsInService,
                $unit->OS ?? 'N/A',
                $unit->windows_serial_number ?? 'N/A',
                $unit->microsoft_serial_number ?? 'N/A',
                $unit->ram ?? 'N/A',
                $unit->storage ?? 'N/A',
                $unit->processor ?? 'N/A',
                $unit->ip_address ?? 'N/A',
                $unit->description ?? 'N/A',
                $unit->created_at,
                $unit->updated_at,
            ];

            // Escape each value for CSV safety (quotes, commas, etc.)
            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    private function escapeCsvValue($value)
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
