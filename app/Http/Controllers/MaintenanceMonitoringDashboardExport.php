<?php

namespace App\Http\Controllers;

use App\Models\Peripherals;
use App\Models\Printer;
use App\Models\SystemUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;

class MaintenanceMonitoringDashboardExport extends Controller
{
    public function export(Request $request)
    {
        $query = $this->buildQuery();

        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('asset_maintenance_logs.id', $ids);
        } else {
            if ($request->filled('tab')) {
                $this->applyTabFilter($query, $request->string('tab')->toString());
            }

            if ($request->filled('search')) {
                $search = $request->string('search')->toString();

                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('asset_maintenance_logs.serial_number', 'like', "%{$search}%")
                        ->orWhere('asset_maintenance_logs.assigned_to', 'like', "%{$search}%")
                        ->orWhere('asset_maintenance_logs.department', 'like', "%{$search}%")
                        ->orWhere('asset_maintenance_logs.item_type', 'like', "%{$search}%")
                        ->orWhere('logs.maintenance_type', 'like', "%{$search}%")
                        ->orWhere('c.name', 'like', "%{$search}%");
                });
            }
        }

        $rows = $query->orderBy('asset_maintenance_logs.id', 'asc')->get();

        $csv = $this->generateCsv($rows);
        $filename = 'maintenance_monitoring_dashboard_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    protected function buildQuery()
    {
        $systemUnitsQuery = DB::table('system_units as su')
            ->selectRaw(
                "(100000000 + su.id) as id,
                CONCAT('SU-', su.id) as display_id,
                ? as maintainable_type,
                su.id as maintainable_id,
                null as item_type,
                COALESCE(su.serial_number, su.asset_code, 'N/A') as serial_number,
                COALESCE((select ac.assigned_to from assigned_computers ac where ac.system_unit_id = su.id limit 1), 'Unassigned') as assigned_to,
                COALESCE((select COALESCE(d.name, 'N/A') from assigned_computers ac left join departments d on ac.department_id = d.id where ac.system_unit_id = su.id limit 1), 'N/A') as department",
                [SystemUnit::class],
            );

        $printersQuery = DB::table('printers as pr')
            ->selectRaw(
                "(200000000 + pr.id) as id,
                CONCAT('PR-', pr.id) as display_id,
                ? as maintainable_type,
                pr.id as maintainable_id,
                null as item_type,
                COALESCE(pr.printer_serial_number, pr.asset_code, 'N/A') as serial_number,
                'Unassigned' as assigned_to,
                COALESCE((select COALESCE(d.name, 'N/A') from departments d where d.id = pr.department_id limit 1), 'N/A') as department",
                [Printer::class],
            );

        $peripheralsQuery = DB::table('peripherals as p')
            ->selectRaw(
                "(300000000 + p.id) as id,
                CONCAT('PE-', p.id) as display_id,
                ? as maintainable_type,
                p.id as maintainable_id,
                UPPER(p.item_type) as item_type,
                COALESCE(p.serial_number, p.asset_code, 'N/A') as serial_number,
                COALESCE((
                    select ac.assigned_to
                    from assigned_computers ac
                    where ac.keyboard_id = p.id
                        or ac.mouse_id = p.id
                        or ac.monitor_id = p.id
                        or ac.ups_id = p.id
                    limit 1
                ), 'Unassigned') as assigned_to,
                COALESCE((
                    select COALESCE(d.name, 'N/A')
                    from assigned_computers ac
                    left join departments d on ac.department_id = d.id
                    where ac.keyboard_id = p.id
                        or ac.mouse_id = p.id
                        or ac.monitor_id = p.id
                        or ac.ups_id = p.id
                    limit 1
                ), 'N/A') as department",
                [Peripherals::class],
            );

        $assetsQuery = $systemUnitsQuery
            ->unionAll($printersQuery)
            ->unionAll($peripheralsQuery);

        $latestLogSubquery = DB::table('asset_maintenance_logs as aml')
            ->selectRaw('aml.maintainable_type, aml.maintainable_id, MAX(aml.id) as latest_log_id')
            ->groupBy('aml.maintainable_type', 'aml.maintainable_id');

        return DB::query()
            ->fromSub($assetsQuery, 'asset_maintenance_logs')
            ->leftJoinSub($latestLogSubquery, 'latest', function ($join): void {
                $join->on('asset_maintenance_logs.maintainable_type', '=', 'latest.maintainable_type')
                    ->on('asset_maintenance_logs.maintainable_id', '=', 'latest.maintainable_id');
            })
            ->leftJoin('asset_maintenance_logs as logs', 'logs.id', '=', 'latest.latest_log_id')
            ->leftJoin('components as c', 'c.id', '=', 'logs.component_id')
            ->leftJoin('office_supplies as os', 'os.id', '=', 'logs.office_supply_id')
            ->selectRaw("asset_maintenance_logs.id, asset_maintenance_logs.display_id, asset_maintenance_logs.maintainable_type, asset_maintenance_logs.maintainable_id, asset_maintenance_logs.item_type, asset_maintenance_logs.serial_number, asset_maintenance_logs.assigned_to, asset_maintenance_logs.department, logs.maintenance_type, logs.maintenance_date as recent_maintenance_date, c.name as component_name, CASE WHEN os.id IS NULL THEN 'N/A' WHEN os.brand IS NOT NULL AND os.brand != '' THEN CONCAT(os.name, ' (', os.brand, ')') ELSE os.name END as replacement_item");
    }

    protected function applyTabFilter(QueryBuilder $query, string $tab): void
    {
        match ($tab) {
            'SYSTEM UNITS' => $query->where('asset_maintenance_logs.maintainable_type', SystemUnit::class),
            'PRINTERS' => $query->where('asset_maintenance_logs.maintainable_type', Printer::class),
            'UPS' => $query->where('asset_maintenance_logs.maintainable_type', Peripherals::class)->where('asset_maintenance_logs.item_type', 'UPS'),
            'MONITOR' => $query->where('asset_maintenance_logs.maintainable_type', Peripherals::class)->where('asset_maintenance_logs.item_type', 'MONITOR'),
            'KEYBOARD' => $query->where('asset_maintenance_logs.maintainable_type', Peripherals::class)->where('asset_maintenance_logs.item_type', 'KEYBOARD'),
            'MOUSE' => $query->where('asset_maintenance_logs.maintainable_type', Peripherals::class)->where('asset_maintenance_logs.item_type', 'MOUSE'),
            default => null,
        };
    }

    protected function generateCsv(Collection $rows): string
    {
        $csv = "\xEF\xBB\xBF";
        $csv .= "ID,Assigned To,Department,Serial Number,Replacement Item,Component,Maintenance Type,Recent Maintenance Date,Days Since Maintenance\n";

        foreach ($rows as $row) {
            $recentDate = $row->recent_maintenance_date
                ? Carbon::parse($row->recent_maintenance_date)->format('M d, Y')
                : 'No maintenance yet';

            $daysSince = 'No maintenance yet';
            if (! empty($row->recent_maintenance_date)) {
                $maintenanceDate = Carbon::parse($row->recent_maintenance_date)->startOfDay();
                $today = now()->startOfDay();

                if ($maintenanceDate->greaterThan($today)) {
                    $daysSince = 'Scheduled in ' . $today->diffInDays($maintenanceDate) . ' days';
                } else {
                    $totalDays = $maintenanceDate->diffInDays($today);
                    $parts = $maintenanceDate->diff($today);
                    $segments = [];

                    if ($parts->m > 0) {
                        $segments[] = $parts->m . ' month' . ($parts->m > 1 ? 's' : '');
                    }

                    if ($parts->d > 0 || empty($segments)) {
                        $segments[] = $parts->d . ' day' . ($parts->d > 1 ? 's' : '');
                    }

                    $daysSince = $totalDays . ' days (' . implode(', ', $segments) . ')';
                }
            }

            $values = [
                $row->display_id,
                $row->assigned_to ?? 'Unassigned',
                $row->department ?? 'N/A',
                $row->serial_number ?? 'N/A',
                $row->replacement_item ?? 'N/A',
                $row->component_name ?? 'N/A',
                $row->maintenance_type ?? 'No maintenance yet',
                $recentDate,
                $daysSince,
            ];

            $csv .= '"' . implode('","', array_map([$this, 'escapeCsvValue'], $values)) . '"' . "\n";
        }

        return $csv;
    }

    protected function escapeCsvValue(mixed $value): string
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
