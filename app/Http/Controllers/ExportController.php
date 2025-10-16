<?php

namespace App\Http\Controllers;

use App\Models\AssignedComputer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    public function exportAssignedComputers(Request $request)
    {
        // Start with base query
        $query = AssignedComputer::with([
            'systemUnit',
            'keyboard',
            'mouse',
            'monitor',
            'ups'
        ]);

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('assigned_to', 'like', "%{$searchTerm}%")
                        ->orWhere('department', 'like', "%{$searchTerm}%")
                        ->orWhereHas('systemUnit', function ($sq) use ($searchTerm) {
                            $sq->where('asset_code', 'like', "%{$searchTerm}%")
                                ->orWhere('serial_number', 'like', "%{$searchTerm}%")
                                ->orWhere('model', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('keyboard', function ($kq) use ($searchTerm) {
                            $kq->where('asset_code', 'like', "%{$searchTerm}%")
                                ->orWhere('serial_number', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('mouse', function ($mq) use ($searchTerm) {
                            $mq->where('asset_code', 'like', "%{$searchTerm}%")
                                ->orWhere('serial_number', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('monitor', function ($monq) use ($searchTerm) {
                            $monq->where('asset_code', 'like', "%{$searchTerm}%")
                                ->orWhere('serial_number', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('ups', function ($uq) use ($searchTerm) {
                            $uq->where('asset_code', 'like', "%{$searchTerm}%")
                                ->orWhere('serial_number', 'like', "%{$searchTerm}%");
                        });
                });
            }

            // Apply specific filters if provided
            if ($request->has('department') && !empty($request->department)) {
                $query->where('department', $request->department);
            }

            // Apply sorting if provided
            if ($request->has('sort') && !empty($request->sort)) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'asc');
                $query->orderBy($sortField, $sortDirection);
            }
        }

        // Get the filtered results
        $assignedComputers = $query->get();

        // Create CSV content 
        $csvContent = $this->generateAssignedComputersCSVFormat($assignedComputers);

        // Generate filename with timestamp and filter info
        $filename = 'assigned_computers_' . date('Y-m-d_H-i-s');
        if ($request->has('search')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        // Return CSV as download
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }





    private function generateAssignedComputersCSVFormat($assignedComputers)
    {

        $title = 'COMPUTER AND PERIPHERALS INVENTORY - ' . now()->format('F d, Y');
        // CSV Headers (same as above)
        $headers = [
            'ID',
            'Assigned To',
            'Department',
            'System Unit ID',
            'System Unit Asset Code',
            'System Unit Model',
            'System Unit Serial Number',
            'System Unit Date Acquired (dd,mm,yyyy)',
            'Operating System',
            'Windows Serial Number',
            'Microsoft Serial Number',
            'RAM',
            'Storage',
            'Processor',
            'IP Address',
            'System Unit Description',
            'Keyboard ID',
            'Keyboard Asset Code',
            'Keyboard Serial Number',
            'Keyboard Model',
            'Keyboard Date Acquired (dd,mm,yyyy)',
            'Keyboard Description',
            'Mouse ID',
            'Mouse Asset Code',
            'Mouse Serial Number',
            'Mouse Model',
            'Mouse Date Acquired (dd,mm,yyyy)',
            'Mouse Description',
            'Monitor ID',
            'Monitor Asset Code',
            'Monitor Serial Number',
            'Monitor Model',
            'Monitor Date Acquired (dd,mm,yyyy)',
            'Monitor Description',
            'UPS ID',
            'UPS Asset Code',
            'UPS Serial Number',
            'UPS Model',
            'UPS Date Acquired (dd,mm,yyyy)',
            'UPS Description',
            'Created At',
            'Updated At'
        ];

        // Start CSV content with headers
        $csv = '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";;

        // Add data rows (use provided collection, not a fresh query)
        foreach ($assignedComputers as $computer) {
            $row = [
                $computer->id,
                $computer->assigned_to,
                $computer->department,
                $computer->system_unit_id,
                $computer->systemUnit?->asset_code ?? 'N/A',
                $computer->systemUnit?->model ?? 'N/A',
                $computer->systemUnit?->serial_number ?? 'N/A',
                $computer->systemUnit?->date_aquired ?? 'N/A',
                $computer->systemUnit?->OS ?? 'N/A',
                $computer->systemUnit?->windows_serial_number ?? 'N/A',
                $computer->systemUnit?->microsoft_serial_number ?? 'N/A',
                $computer->systemUnit?->ram ?? 'N/A',
                $computer->systemUnit?->storage ?? 'N/A',
                $computer->systemUnit?->processor ?? 'N/A',
                $computer->systemUnit?->ip_address ?? 'N/A',
                $computer->systemUnit?->description ?? 'N/A',
                $computer->keyboard_id ?? 'N/A',
                $computer->keyboard?->asset_code ?? 'N/A',
                $computer->keyboard?->serial_number ?? 'N/A',
                $computer->keyboard?->model ?? 'N/A',
                $computer->keyboard?->date_acquired ?? 'N/A',
                $computer->keyboard?->description ?? 'N/A',
                $computer->mouse_id ?? 'N/A',
                $computer->mouse?->asset_code ?? 'N/A',
                $computer->mouse?->serial_number ?? 'N/A',
                $computer->mouse?->model ?? 'N/A',
                $computer->mouse?->date_acquired ?? 'N/A',
                $computer->mouse?->description ?? 'N/A',
                $computer->monitor_id ?? 'N/A',
                $computer->monitor?->asset_code ?? 'N/A',
                $computer->monitor?->serial_number ?? 'N/A',
                $computer->monitor?->model ?? 'N/A',
                $computer->monitor?->date_acquired ?? 'N/A',
                $computer->monitor?->description ?? 'N/A',
                $computer->ups_id ?? 'N/A',
                $computer->ups?->asset_code ?? 'N/A',
                $computer->ups?->serial_number ?? 'N/A',
                $computer->ups?->model ?? 'N/A',
                $computer->ups?->date_acquired ?? 'N/A',
                $computer->ups?->description ?? 'N/A',
                $computer->created_at,
                $computer->updated_at,
            ];

            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    public function exportBusDailyChecklist(Request $request)
    {
        $query = \App\Models\BusDailyChecklist::with('bus');

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('check_date', 'like', "%{$searchTerm}%")
                        ->orWhere('remarks', 'like', "%{$searchTerm}%")
                        ->orWhereHas('bus', function ($bq) use ($searchTerm) {
                            $bq->where('bus_number', 'like', "%{$searchTerm}%")
                                ->orWhere('model', 'like', "%{$searchTerm}%")
                                ->orWhere('status', 'like', "%{$searchTerm}%")
                                ->orWhere('base_location', 'like', "%{$searchTerm}%");
                        });
                });
            }
            if ($request->has('checked') && $request->checked !== '') {
                $query->where('checked', (bool)$request->checked);
            }
            if ($request->has('year') && !empty($request->year)) {
                $query->whereYear('check_date', (int)$request->year);
            }
            if ($request->has('date') && !empty($request->date)) {
                $query->whereDate('check_date', $request->date);
            }
        }

        $query->orderBy('id', 'desc');
        $busDailyChecklists = $query->get();

        $csvContent = $this->generateBusDailyChecklistCSV($busDailyChecklists);
        $filename = 'bus_daily_checklist_' . date('Y-m-d_H-i-s');
        if ($request->has('search') || $request->has('checked') || $request->has('year') || $request->has('ids')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateBusDailyChecklistCSV($busDailyChecklists)
    {
        // CSV Headers
        $headers = [
            'ID',
            'Bus ID',
            'Bus Number',
            'Bus Model',
            'Bus Status',
            'Bus Base Location',
            'Check Date',
            'Checked',
            'Remarks',
            'Created At',
            'Updated At'
        ];

        // Start CSV content with headers
        $csv = '"' . implode('","', $headers) . '"' . "\n";

        // Add data rows
        foreach ($busDailyChecklists as $checklist) {
            $row = [
                $checklist->id,
                $checklist->bus_id,
                $checklist->bus?->bus_number ?? 'N/A',
                $checklist->bus?->model ?? 'N/A',
                $checklist->bus?->status ?? 'N/A',
                $checklist->bus?->base_location ?? 'N/A',
                $checklist->check_date,
                $checklist->checked ? 'Yes' : 'No',
                $checklist->remarks ?? 'N/A',
                $checklist->created_at,
                $checklist->updated_at,
            ];

            // Escape and add row to CSV
            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    private function escapeCsvValue($value)
    {
        // Handle null values
        if ($value === null) {
            return 'N/A';
        }

        // Convert to string and escape quotes
        $value = (string) $value;
        return str_replace('"', '""', $value);
    }
}
