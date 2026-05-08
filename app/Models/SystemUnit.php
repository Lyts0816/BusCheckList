<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUnit extends Model
{
    protected $table = 'system_units';

    protected $fillable = [
        'asset_code',
        'asset_type',
        'serial_number',
        'model',
        'date_aquired',
        'OS',
        'windows_serial_number',
        'microsoft_serial_number',
        'ram',
        'storage',
        'processor',
        'ip_address',
        'description',
        'assigned_to',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function assignedComputer()
    {
        return $this->hasOne(AssignedComputer::class, 'system_unit_id');
    }

    public function maintenanceLogs()
    {
        return $this->morphMany(AssetMaintenanceLog::class, 'maintainable');
    }
}
