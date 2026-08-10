<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peripherals;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class PeripheralsExport extends Controller
{
    public function exportPeripherals(Request $request)
    {
        // Aggregate department per peripheral per role (one row per peripheral_id)
        $subKeyboard = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('keyboard_id as peripheral_id, MAX(departments.name) as dept, MAX(assigned_computers.assigned_to) as assigned_to')
            ->whereNotNull('keyboard_id')
            ->groupBy('keyboard_id');

        $subMouse = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('mouse_id as peripheral_id, MAX(departments.name) as dept, MAX(assigned_computers.assigned_to) as assigned_to')
            ->whereNotNull('mouse_id')
            ->groupBy('mouse_id');

        $subMonitor = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('monitor_id as peripheral_id, MAX(departments.name) as dept, MAX(assigned_computers.assigned_to) as assigned_to')
            ->whereNotNull('monitor_id')
            ->groupBy('monitor_id');

        $subUps = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('ups_id as peripheral_id, MAX(departments.name) as dept, MAX(assigned_computers.assigned_to) as assigned_to')
            ->whereNotNull('ups_id')
            ->groupBy('ups_id');

        // Base query
        $query = Peripherals::query()
            ->leftJoinSub($subKeyboard, 'ac_k', 'peripherals.id', '=', 'ac_k.peripheral_id')
            ->leftJoinSub($subMouse, 'ac_m', 'peripherals.id', '=', 'ac_m.peripheral_id')
            ->leftJoinSub($subMonitor, 'ac_mon', 'peripherals.id', '=', 'ac_mon.peripheral_id')
            ->leftJoinSub($subUps, 'ac_u', 'peripherals.id', '=', 'ac_u.peripheral_id')
            ->leftJoin('departments as p_dept', 'peripherals.department_id', '=', 'p_dept.id')
            ->select('peripherals.*')
            ->selectRaw('COALESCE(ac_k.dept, ac_m.dept, ac_mon.dept, ac_u.dept, p_dept.name) as department_sort')
            ->selectRaw('COALESCE(ac_k.assigned_to, ac_m.assigned_to, ac_mon.assigned_to, ac_u.assigned_to, peripherals.assigned_to) as assigned_to_sort')
            ->with(['maintenanceLogs.officeSupply']);

        // BULK (selected IDs) vs FULL (all)
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('peripherals.id', $ids, 'and', false);
        } else {
            if ($request->filled('search')) {
                $s = $request->string('search');
                $query->where(function ($q) use ($s) {
                    $q->where('item_type', 'like', "%{$s}%")
                      ->orWhere('asset_code', 'like', "%{$s}%")
                      ->orWhere('serial_number', 'like', "%{$s}%")
                      ->orWhere('model', 'like', "%{$s}%")
                      ->orWhere('date_acquired', 'like', "%{$s}%")
                                            ->orWhere('description', 'like', "%{$s}%")
                                            ->orWhere('peripherals.assigned_to', 'like', "%{$s}%")
                                            ->orWhere('p_dept.name', 'like', "%{$s}%");
                });
            }
            if ($request->filled('sort')) {
                $sortField = $request->get('sort');
                $sortDirection = $request->get('direction', 'asc');
                $whitelist = ['id','item_type','asset_code','serial_number','model','date_acquired','description','created_at','updated_at'];
                if (in_array($sortField, $whitelist, true)) {
                    $query->orderBy($sortField, $sortDirection);
                }
            }
        }

        // Order: non-null departments first (A-Z), then nulls, then newest id
        $query->orderByRaw('department_sort IS NULL ASC', [])
              ->orderBy('department_sort', 'asc')
              ->orderByRaw('assigned_to_sort IS NULL ASC', [])
              ->orderBy('assigned_to_sort', 'asc')
              ->orderBy('peripherals.id', 'desc');

        $peripherals = $query->get();

        // Optional: touch accessor for assignedComputers if you rely on it below
        $peripherals->each(fn ($p) => $p->assignedComputers);

        $csvContent = $this->generatePeripheralsCSVFormat($peripherals);

        $filename = 'peripherals_' . date('Y-m-d_H-i-s');
        if ($request->has('search')) $filename .= '_filtered';
        if ($request->has('ids')) $filename .= '_selected';
        $filename .= '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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

    private function generatePeripheralsCSVFormat(Collection $peripherals): string
    {
        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csv .= "ID,Item Type,Status,Asset Code,Serial Number,Model,Date Acquired,Description,Assigned To,Department,Replacement Item,Days Since Maintenance,Years In Service,Created At,Updated At\n";

        foreach ($peripherals as $peripheral) {
            $department = $peripheral->department_sort ?? '';
            $assignedTo = $peripheral->assigned_to_sort ?? '';
            $replacementItem = $this->getReplacementItem($peripheral);
            $daysSinceMaintenance = $this->getDaysSinceMaintenance($peripheral);
            $yearsInService = $this->getYearsInService($peripheral);
            $csv .= '"' . $this->escapeCsvValue($peripheral->id) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->item_type) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->status ?? '') . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->asset_code) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->serial_number) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->model) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->date_acquired) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->description) . '",';
            $csv .= '"' . $this->escapeCsvValue($assignedTo) . '",';
            $csv .= '"' . $this->escapeCsvValue($department) . '",';
            $csv .= '"' . $this->escapeCsvValue($replacementItem) . '",';
            $csv .= '"' . $this->escapeCsvValue($daysSinceMaintenance) . '",';
            $csv .= '"' . $this->escapeCsvValue($yearsInService) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->created_at) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->updated_at) . '"';
            $csv .= "\n";
        }

        return $csv;
    }

    private function getDaysSinceMaintenance(Peripherals $peripheral): string
    {
        $latestMaintenanceDate = $peripheral->maintenanceLogs()
            ->whereNotNull('maintenance_date')
            ->max('maintenance_date');

        if (! $latestMaintenanceDate) {
            return 'No maintenance yet';
        }

        $maintenanceDate = Carbon::parse($latestMaintenanceDate)->startOfDay();
        $today = now()->startOfDay();

        if ($maintenanceDate->greaterThan($today)) {
            return 'Scheduled in ' . $today->diffInDays($maintenanceDate) . ' days';
        }

        $totalDays = $maintenanceDate->diffInDays($today);
        $parts = $maintenanceDate->diff($today);

        $segments = [];

        if ($parts->m > 0) {
            $segments[] = $parts->m . ' month' . ($parts->m > 1 ? 's' : '');
        }

        if ($parts->d > 0 || empty($segments)) {
            $segments[] = $parts->d . ' day' . ($parts->d > 1 ? 's' : '');
        }

        return $totalDays . ' days (' . implode(', ', $segments) . ')';
    }

    private function getReplacementItem(Peripherals $peripheral): string
    {
        $replacementLog = $peripheral->maintenanceLogs()
            ->with('officeSupply')
            ->where('maintenance_type', 'replacement')
            ->whereNotNull('office_supply_id')
            ->latest('maintenance_date')
            ->first();

        if (! $replacementLog?->officeSupply) {
            return 'N/A';
        }

        return $replacementLog->officeSupply->brand
            ? $replacementLog->officeSupply->name . ' (' . $replacementLog->officeSupply->brand . ')'
            : $replacementLog->officeSupply->name;
    }

    private function getYearsInService(Peripherals $peripheral): string
    {
        if (! $peripheral->date_acquired) {
            return 'N/A';
        }

        $diff = Carbon::parse($peripheral->date_acquired)->diff(now());

        $years = $diff->y;
        $months = $diff->m;

        $yearLabel = $years === 1 ? 'year' : 'years';
        $monthLabel = $months === 1 ? 'month' : 'months';

        return "{$years} {$yearLabel}, {$months} {$monthLabel}";
    }
}
