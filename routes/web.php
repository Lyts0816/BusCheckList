<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeaveLogExportController;
use App\Http\Controllers\MaintenanceMonitoringDashboardExport;
use App\Http\Controllers\PeripheralsExport;
use App\Http\Controllers\PrinterExport;
use App\Http\Controllers\SystemUnitExport;
use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/admin/login');

// Export routes (protected by auth middleware)
Route::middleware('auth')->group(function () {

    Route::get('/export/assigned-computers', [ExportController::class, 'exportAssignedComputers'])
        ->name('export.assigned-computers');

    Route::get('/export/bus-daily-checklist', [ExportController::class, 'exportBusDailyChecklist'])
        ->name('export.bus-daily-checklist');

    Route::get('/export/dispatched-trips', [ExportController::class, 'exportDispatchedTrips'])
        ->name('export.dispatched-trips');

    Route::get('/export/dispatched-trips-pdf', [ExportController::class, 'exportDispatchedTripsPDF'])
        ->name('export.dispatched-trips-pdf');

    Route::get('/export/conductors', [ExportController::class, 'exportConductors'])
        ->name('export.conductors');

    Route::get('/export/drivers', [ExportController::class, 'exportDrivers'])
        ->name('export.drivers');

    Route::get('/export/asset-maintenance-logs', [ExportController::class, 'exportAssetMaintenanceLogs'])
        ->name('export.asset-maintenance-logs');

    Route::get('/export/printers', [PrinterExport::class, 'exportAssignedPrinters'])
        ->name('export.printers');

    Route::get('/export/system-units', [SystemUnitExport::class, 'exportAssignedSystemUnits'])
        ->name('export.system-units');

    Route::get('/export/peripherals', [PeripheralsExport::class, 'exportPeripherals'])
        ->name('export.peripherals');

    Route::get('/export/maintenance-monitoring-dashboard', [MaintenanceMonitoringDashboardExport::class, 'export'])
        ->name('export.maintenance-monitoring-dashboard');

    Route::get('/export/leave-logs', [LeaveLogExportController::class, 'exportExcel'])
        ->name('export.leave-logs');

    Route::post('/export/leave-logs/excel', [LeaveLogExportController::class, 'exportExcel'])
        ->name('export.leave-logs.excel');

    Route::get('/export/leave-logs/print/{id}', [LeaveLogExportController::class, 'printPreview'])
        ->name('export.leave-logs.print');

    Route::get('/export/leave-logs/all-excel', [LeaveLogExportController::class, 'exportAllExcel'])
        ->name('export.leave-logs.all-excel');

    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/shipments/{shipment}/print', function (Shipment $shipment) {

        $pdf = Pdf::loadView('pdf.shipment-label', [
            'shipment' => $shipment,
        ]);

        // 3x4 inches label size (approx)
        $pdf->setPaper([0, 0, 216, 288], 'portrait');

        return $pdf->stream('shipment-label.pdf');

    })->name('shipments.print');

});
