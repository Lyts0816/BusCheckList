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
            ->selectRaw('keyboard_id as peripheral_id, MAX(departments.name) as dept')
            ->whereNotNull('keyboard_id')
            ->groupBy('keyboard_id');

        $subMouse = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('mouse_id as peripheral_id, MAX(departments.name) as dept')
            ->whereNotNull('mouse_id')
            ->groupBy('mouse_id');

        $subMonitor = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('monitor_id as peripheral_id, MAX(departments.name) as dept')
            ->whereNotNull('monitor_id')
            ->groupBy('monitor_id');

        $subUps = DB::table('assigned_computers')
            ->leftJoin('departments', 'assigned_computers.department_id', '=', 'departments.id')
            ->selectRaw('ups_id as peripheral_id, MAX(departments.name) as dept')
            ->whereNotNull('ups_id')
            ->groupBy('ups_id');

        // Base query
        $query = Peripherals::query()
            ->leftJoinSub($subKeyboard, 'ac_k', 'peripherals.id', '=', 'ac_k.peripheral_id')
            ->leftJoinSub($subMouse, 'ac_m', 'peripherals.id', '=', 'ac_m.peripheral_id')
            ->leftJoinSub($subMonitor, 'ac_mon', 'peripherals.id', '=', 'ac_mon.peripheral_id')
            ->leftJoinSub($subUps, 'ac_u', 'peripherals.id', '=', 'ac_u.peripheral_id')
            ->select('peripherals.*')
            ->selectRaw('COALESCE(ac_k.dept, ac_m.dept, ac_mon.dept, ac_u.dept) as department_sort');

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
                      ->orWhere('description', 'like', "%{$s}%");
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
        $csv .= "ID,Item Type,Asset Code,Serial Number,Model,Date Acquired,Description,Department,Created At,Updated At\n";

        foreach ($peripherals as $peripheral) {
            $department = $peripheral->department_sort ?? '';
            $csv .= '"' . $this->escapeCsvValue($peripheral->id) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->item_type) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->asset_code) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->serial_number) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->model) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->date_acquired) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->description) . '",';
            $csv .= '"' . $this->escapeCsvValue($department) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->created_at) . '",';
            $csv .= '"' . $this->escapeCsvValue($peripheral->updated_at) . '"';
            $csv .= "\n";
        }

        return $csv;
    }
}
