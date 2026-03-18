<?php

namespace App\Http\Controllers;

use App\Exports\LeaveLogExporter;
use App\Models\LeaveLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeaveLogExportController extends Controller
{
    /**
     * Export single or multiple leave logs to Excel
     */
    public function exportExcel(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['error' => 'No records selected'], 422);
        }

        $idArray = is_array($ids) ? $ids : explode(',', $ids);

        $exporter = (new LeaveLogExporter())->setIds($idArray);

        return Excel::download(
            $exporter,
            'leave-logs_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Show print preview for single leave log
     */
    public function printPreview($id)
    {
        $leaveLog = LeaveLog::with('employee')->findOrFail($id);

        return view('exports.leave-log-print', ['leaveLog' => $leaveLog]);
    }

    /**
     * Export all leave logs to Excel with filters
     */
    public function exportAllExcel(Request $request)
    {
        $query = LeaveLog::with('employee');

        // Apply date range filter if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('date_filed', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('date_filed', '<=', $request->to_date);
        }

        // Apply leave type filter if provided
        if ($request->has('leave_type') && $request->leave_type) {
            $query->where('leave_type', $request->leave_type);
        }

        // Apply employee filter if provided
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        return Excel::download(
            new LeaveLogExporter(),
            'leave-logs-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
