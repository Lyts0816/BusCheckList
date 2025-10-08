<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

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
});