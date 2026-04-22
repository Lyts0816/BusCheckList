<?php

namespace App\Http\Controllers;

use App\Models\AssetMaintenanceLog;
use App\Models\AssignedComputer;
use App\Models\Conductors;
use App\Models\Drivers;
use App\Models\DispatchedTrips;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\PDF;

class ExportController extends Controller
{
    // ===================================================================
    // ASSET MAINTENANCE LOGS EXPORT
    // ===================================================================

    public function exportAssetMaintenanceLogs(Request $request)
    {
        $query = AssetMaintenanceLog::query()->with(['component', 'maintainable']);

        if ($request->has('ids') && ! empty($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            if ($request->has('search') && ! empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm): void {
                    $q->where('maintenance_type', 'like', "%{$searchTerm}%")
                        ->orWhere('performed_by', 'like', "%{$searchTerm}%")
                        ->orWhere('issue_reported', 'like', "%{$searchTerm}%")
                        ->orWhere('action_taken', 'like', "%{$searchTerm}%")
                        ->orWhere('remarks', 'like', "%{$searchTerm}%")
                        ->orWhereHas('component', function ($cq) use ($searchTerm): void {
                            $cq->where('name', 'like', "%{$searchTerm}%");
                        });
                });
            }
        }

        $query->orderBy('id', 'desc');
        $logs = $query->get();

        $csv = $this->generateAssetMaintenanceLogsCsv($logs);

        $filename = 'asset_maintenance_logs_' . date('Y-m-d_H-i-s');
        if ($request->has('search')) {
            $filename .= '_filtered';
        }
        if ($request->has('ids')) {
            $filename .= '_selected';
        }
        $filename .= '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateAssetMaintenanceLogsCsv($logs)
    {
        $title = 'ASSET MAINTENANCE LOGS - ' . now()->format('F d, Y');
        $headers = [
            'ID',
            'Asset Type',
            'Asset',
            'Component',
            'Maintenance Type',
            'Maintenance Date',
            'Performed By',
            'Issue Reported',
            'Action Taken',
            'Cost',
            'Remarks',
            'Created At',
            'Updated At',
        ];

        $csv  = "\xEF\xBB\xBF";
        $csv .= '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";

        foreach ($logs as $log) {
            $assetType = class_basename((string) $log->maintainable_type);
            $assetType = preg_replace('/(?<!^)([A-Z])/', ' $1', $assetType) ?: 'N/A';

            $asset = data_get($log, 'maintainable.asset_code')
                ?? data_get($log, 'maintainable.name')
                ?? $log->maintainable_id
                ?? 'N/A';

            $row = [
                $log->id,
                $assetType,
                $asset,
                $log->component?->name ?? 'N/A',
                $log->maintenance_type ?? 'N/A',
                $log->maintenance_date ?? 'N/A',
                $log->performed_by ?? 'N/A',
                $log->issue_reported ?? 'N/A',
                $log->action_taken ?? 'N/A',
                $log->cost ?? 'N/A',
                $log->remarks ?? 'N/A',
                $log->created_at,
                $log->updated_at,
            ];

            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    // ===================================================================
    // ASSIGNED COMPUTERS EXPORT
    // ===================================================================

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
            'System Unit Serial Number',
            'System Unit Model',
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
        $csv  = '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";

        // Add data rows (use provided collection, not a fresh query)
        foreach ($assignedComputers as $computer) {
            $row = [
                $computer->id,
                $computer->assigned_to,
                $computer->department,
                $computer->system_unit_id,
                $computer->systemUnit?->asset_code ?? 'N/A',
                $this->preserveCsvText($computer->systemUnit?->serial_number),
                $computer->systemUnit?->model ?? 'N/A',
                $computer->systemUnit?->date_aquired ?? 'N/A',
                $computer->systemUnit?->OS ?? 'N/A',
                $this->preserveCsvText($computer->systemUnit?->windows_serial_number),
                $this->preserveCsvText($computer->systemUnit?->microsoft_serial_number),
                $computer->systemUnit?->ram ?? 'N/A',
                $computer->systemUnit?->storage ?? 'N/A',
                $computer->systemUnit?->processor ?? 'N/A',
                $computer->systemUnit?->ip_address ?? 'N/A',
                $computer->systemUnit?->description ?? 'N/A',
                $computer->keyboard_id ?? 'N/A',
                $computer->keyboard?->asset_code ?? 'N/A',
                $this->preserveCsvText($computer->keyboard?->serial_number),
                $computer->keyboard?->model ?? 'N/A',
                $computer->keyboard?->date_acquired ?? 'N/A',
                $computer->keyboard?->description ?? 'N/A',
                $computer->mouse_id ?? 'N/A',
                $computer->mouse?->asset_code ?? 'N/A',
                $this->preserveCsvText($computer->mouse?->serial_number),
                $computer->mouse?->model ?? 'N/A',
                $computer->mouse?->date_acquired ?? 'N/A',
                $computer->mouse?->description ?? 'N/A',
                $computer->monitor_id ?? 'N/A',
                $computer->monitor?->asset_code ?? 'N/A',
                $this->preserveCsvText($computer->monitor?->serial_number),
                $computer->monitor?->model ?? 'N/A',
                $computer->monitor?->date_acquired ?? 'N/A',
                $computer->monitor?->description ?? 'N/A',
                $computer->ups_id ?? 'N/A',
                $computer->ups?->asset_code ?? 'N/A',
                $this->preserveCsvText($computer->ups?->serial_number),
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

    // ===================================================================
    // BUS DAILY CHECKLIST EXPORT
    // ===================================================================

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
        $title = 'BUS CHECKLIST REPORT - ' . now()->format('F d, Y');
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

        $csv  = '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";;

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

    // ===================================================================
    // CSV HELPER METHODS
    // ===================================================================

    private function escapeCsvValue($value)
    {
        // Handle null values
        if ($value === null) {
            return 'N/A';
        }

        // Convert to string
        $value = (string) $value;

        // Normalize non-breaking spaces and mis-decoded UTF-8 ("Â ")
        $value = str_replace(["\xC2\xA0", "Â "], ' ', $value);

        // Collapse excess whitespace at start/end
        $value = trim($value);

        // Keep long/leading-zero digit strings as text so Excel won't convert to scientific notation
        if (
            $value !== '' &&
            !str_starts_with($value, '="') &&
            preg_match('/^\d+$/', $value) &&
            (strlen($value) >= 12 || (strlen($value) > 1 && str_starts_with($value, '0')))
        ) {
            $value = '="' . $value . '"';
        }

        // Escape quotes for CSV
        return str_replace('"', '""', $value);
    }

    private function preserveCsvText($value)
    {
        if ($value === null || $value === '') {
            return 'N/A';
        }

        return '="' . (string) $value . '"';
    }

    // ===================================================================
    // CONDUCTORS EXPORT
    // ===================================================================

    public function exportConductors(Request $request)
    {
        $query = Conductors::query();

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('conductor_name', 'like', "%{$searchTerm}%")
                        ->orWhere('status', 'like', "%{$searchTerm}%")
                        ->orWhere('remarks', 'like', "%{$searchTerm}%");
                });
            }

            // Apply sorting if provided
            if ($request->has('sort') && !empty($request->sort)) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'asc');
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
            }
        }

        $conductors = $query->get();
        $csvContent = $this->generateConductorsCSV($conductors);

        $filename = 'conductors_' . date('Y-m-d_H-i-s');
        if ($request->has('search') || $request->has('ids')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateConductorsCSV($conductors)
    {
        $title = 'CONDUCTORS LIST - ' . now()->format('F d, Y');
        $headers = [
            'ID',
            'Conductor Name',
            'Status',
            'Remarks',
            'Created At',
            'Updated At'
        ];

        // Start with UTF-8 BOM
        $csv = "\xEF\xBB\xBF";
        $csv .= '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";

        foreach ($conductors as $conductor) {
            $row = [
                $conductor->id,
                $conductor->conductor_name,
                $conductor->status,
                $conductor->remarks ?? 'N/A',
                $conductor->created_at,
                $conductor->updated_at,
            ];

            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    // ===================================================================
    // DRIVERS EXPORT
    // ===================================================================

    public function exportDrivers(Request $request)
    {
        $query = Drivers::query();

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('driver_name', 'like', "%{$searchTerm}%")
                        ->orWhere('status', 'like', "%{$searchTerm}%")
                        ->orWhere('remarks', 'like', "%{$searchTerm}%");
                });
            }

            // Apply sorting if provided
            if ($request->has('sort') && !empty($request->sort)) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'asc');
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
            }
        }

        $drivers = $query->get();
        $csvContent = $this->generateDriversCSV($drivers);

        $filename = 'drivers_' . date('Y-m-d_H-i-s');
        if ($request->has('search') || $request->has('ids')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateDriversCSV($drivers)
    {
        $title = 'DRIVERS LIST - ' . now()->format('F d, Y');
        $headers = [
            'ID',
            'Driver Name',
            'Status',
            'Remarks',
            'Created At',
            'Updated At'
        ];

        // Start with UTF-8 BOM
        $csv = "\xEF\xBB\xBF";
        $csv .= '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";

        foreach ($drivers as $driver) {
            $row = [
                $driver->id,
                $driver->driver_name,
                $driver->status,
                $driver->remarks ?? 'N/A',
                $driver->created_at,
                $driver->updated_at,
            ];

            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    // ===================================================================
    // DISPATCHED TRIPS EXPORT
    // ===================================================================

    public function exportDispatchedTrips(Request $request)
    {
        // Start with base query - load all necessary relationships
        $query = DispatchedTrips::with([
            'dispatchSheet.route',
            'busNumber',
            'natureOfTrip',
        ]);

        if ($request->filled('dispatch_sheet_id')) {
            $query->where('dispatch_sheet_id', $request->dispatch_sheet_id);
        }

        // Bulk export: if ids are provided, only export those
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('trip_number', 'like', "%{$searchTerm}%")
                        ->orWhereHas('busNumber', function ($bq) use ($searchTerm) {
                            $bq->where('bus_number', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('dispatchSheet.route', function ($rq) use ($searchTerm) {
                            $rq->where('from', 'like', "%{$searchTerm}%")
                                ->orWhere('to', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('busNumber', function ($bq) use ($searchTerm) {
                            $bq->where('bus_class', 'like', "%{$searchTerm}%");
                        })
                        ;
                });
            }

           
        }

        // Get the filtered results
        $dispatchedTrips = $query->get();

        // Create CSV content 
        $csvContent = $this->generateDispatchedTripsCSV($dispatchedTrips);

        // Generate filename with timestamp and filter info
        $filename = 'dispatched_trips_' . date('Y-m-d_H-i-s');
        if ($request->has('search') || $request->has('ids')) {
            $filename .= '_filtered';
        }
        $filename .= '.csv';

        // Return CSV as download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateDispatchedTripsCSV($dispatchedTrips)
    {
        $title = 'DISPATCHED TRIPS REPORT - ' . now()->format('F d, Y');
        
        // CSV Headers matching the infolist structure
        $headers = [
            'Trip Number',
            'Dispatch Date',
            'Route (From)',
            'Route (To)',
            'KM Run',
            'Nature of Trip',
            'Bus Number',
            'Bus Class',
            'Driver',
            'Conductor',
            'Time in Terminal',
            'Time of Parking',
            'Time of Arrival',
            'Time of Departure',
            'Idle Time Start',
            'Idle Time End',
            'Total Travel Time',
            'Total Add Time',
            'Ticket Serial #',
            'Passengers on Board',
            'Baggage Amount',
            'Baggage Ticket #',
            'Remarks'
        ];

        // Start CSV content with UTF-8 BOM, title and headers
        $csv  = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csv .= '"' . $title . '"' . "\n\n";
        $csv .= '"' . implode('","', $headers) . '"' . "\n";

        // Add data rows
        foreach ($dispatchedTrips as $trip) {
            // Format total travel time
            $travelTimeFormatted = 'N/A';
            if ($trip->total_travel_time_minutes) {
                $hours = intdiv($trip->total_travel_time_minutes, 60);
                $minutes = $trip->total_travel_time_minutes % 60;
                $hourLabel = $hours !== 1 ? 'hours' : 'hour';
                $minuteLabel = $minutes !== 1 ? 'minutes' : 'minute';
                $travelTimeFormatted = "{$hours} {$hourLabel} and {$minutes} {$minuteLabel}";
            }

            // Format total add time
            $addTimeFormatted = 'N/A';
            if ($trip->total_add_time_minutes) {
                $addHours = intdiv($trip->total_add_time_minutes, 60);
                $addMinutes = $trip->total_add_time_minutes % 60;
                $addHourLabel = $addHours !== 1 ? 'hours' : 'hour';
                $addMinuteLabel = $addMinutes !== 1 ? 'minutes' : 'minute';
                $addTimeFormatted = "{$addHours} {$addHourLabel} and {$addMinutes} {$addMinuteLabel}";
            }

            // Format baggage amount
            $baggageAmount = $trip->baggage_amount ? number_format($trip->baggage_amount, 2) : 'N/A';

            $row = [
                $trip->trip_number ?? 'N/A',
                $trip->dispatchSheet?->dispatch_date ? date('M d, Y', strtotime($trip->dispatchSheet->dispatch_date)) : 'N/A',
                $trip->dispatchSheet?->route?->from ?? 'N/A',
                $trip->dispatchSheet?->route?->to ?? 'N/A',
                $trip->dispatchSheet?->distance_at_dispatch ? $trip->dispatchSheet->distance_at_dispatch . ' km' : 'N/A',
                $trip->natureOfTrip?->nature_of_trip_name ?? 'N/A',
                $trip->busNumber?->bus_number ?? 'N/A',
                $trip->busNumber?->bus_class ?? 'N/A',
                $trip->snap_drivers ?? 'N/A',
                $trip->snap_conductors ?? 'N/A',
                $trip->time_in_terminal ? $trip->time_in_terminal->format('h:i A') : 'N/A',
                $trip->time_of_parking ? $trip->time_of_parking->format('h:i A') : 'N/A',
                $trip->time_of_arrival ? $trip->time_of_arrival->format('h:i A') : 'N/A',
                $trip->time_of_departure ? $trip->time_of_departure->format('h:i A') : 'N/A',
                $trip->idle_time_start ? $trip->idle_time_start->format('h:i A') : 'N/A',
                $trip->idle_time_end ? $trip->idle_time_end->format('h:i A') : 'N/A',
                $travelTimeFormatted,
                $addTimeFormatted,
                $trip->ticket_number ?? 'N/A',
                $trip->passengers_on_board ?? 'N/A',
                $baggageAmount,
                $trip->baggage_ticket_no ?? 'N/A',
                $trip->remarks ?? 'N/A',
            ];

            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $row)) . '"' . "\n";
        }

        return $csv;
    }

    // ===================================================================
    // DISPATCHED TRIPS PDF EXPORT
    // ===================================================================

    public function exportDispatchedTripsPDF(Request $request)
    {
        $query = DispatchedTrips::with([
            'dispatchSheet.route',
            'busNumber',
            'natureOfTrip',
        ]);

        if ($request->filled('dispatch_sheet_id')) {
            $query->where('dispatch_sheet_id', $request->dispatch_sheet_id);
        }

        
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('trip_number', 'like', "%{$searchTerm}%")
                        ->orWhereHas('busNumber', function ($bq) use ($searchTerm) {
                            $bq->where('bus_number', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('dispatchSheet.route', function ($rq) use ($searchTerm) {
                            $rq->where('from', 'like', "%{$searchTerm}%")
                                ->orWhere('to', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('busNumber', function ($bq) use ($searchTerm) {
                            $bq->where('bus_class', 'like', "%{$searchTerm}%");
                        });
                });
            }

            
            if ($request->has('sort') && !empty($request->sort)) {
                $sortField = $request->sort;
                $sortDirection = $request->get('direction', 'desc');
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
            }
        }

        $dispatchedTrips = $query->get();

        $pdf = PDF::loadView('exports.dispatched-trips-pdf', [
            'trips' => $dispatchedTrips,
            'exportDate' => now()->format('F d, Y'),
        ]);

        $filename = 'dispatched_trips_' . date('Y-m-d_H-i-s');
        if ($request->has('search') || $request->has('ids')) {
            $filename .= '_filtered';
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }
}
