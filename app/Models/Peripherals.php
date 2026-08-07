<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peripherals extends Model
{
    protected $table = 'peripherals';

    protected $fillable = [
        'item_type',
        'asset_code',
        'serial_number',
        'model',
        'date_acquired',
        'description',
        'assigned_to',
        'department_id',
        'status',
        'image',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function assignedKeyboards()
    {
        return $this->hasMany(AssignedComputer::class, 'keyboard_id');
    }
    public function assignedMice()
    {
        return $this->hasMany(AssignedComputer::class, 'mouse_id');
    }
    public function assignedMonitors()
    {
        return $this->hasMany(AssignedComputer::class, 'monitor_id');
    }
    public function assignedUps()
    {
        return $this->hasMany(AssignedComputer::class, 'ups_id');
    }

    // Corrected method to get all assigned computers for this peripheral
    public function getAssignedComputersAttribute()
    {
        return AssignedComputer::where('keyboard_id', '=', $this->id, 'and')
            ->orWhere('mouse_id', '=', $this->id)
            ->orWhere('monitor_id', '=', $this->id)
            ->orWhere('ups_id', '=', $this->id)
            ->get();
    }

    public function maintenanceLogs()
    {
        return $this->morphMany(AssetMaintenanceLog::class, 'maintainable');
    }
}
