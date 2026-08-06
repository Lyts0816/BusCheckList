<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $table = 'printers';

    protected $fillable = [
        'department_id',
        'status',
        'printer_host',
        'printer_model',
        'asset_code',
        'printer_serial_number',
        'date_aquired',
        'description',
    ];

    public function maintenanceLogs()
    {
        return $this->morphMany(AssetMaintenanceLog::class, 'maintainable');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }
}
