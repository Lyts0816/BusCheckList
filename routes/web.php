<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PrinterExport;
use App\Http\Controllers\SystemUnitExport;
use App\Http\Controllers\PeripheralsExport;
use App\Http\Controllers\Auth\LogoutController;

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
    Route::get('/export/printers', [PrinterExport::class, 'exportAssignedPrinters'])
        ->name('export.printers');
    Route::get('/export/system-units', [SystemUnitExport::class, 'exportAssignedSystemUnits'])
        ->name('export.system-units');
    Route::get('/export/peripherals', [PeripheralsExport::class, 'exportPeripherals'])
        ->name('export.peripherals');
    Route::post('/logout', LogoutController::class)->name('logout');
});