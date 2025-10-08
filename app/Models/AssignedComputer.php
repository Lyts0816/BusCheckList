<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedComputer extends Model
{
    protected $fillable = [
        'system_unit_id',
        'keyboard_id',
        'mouse_id',
        'monitor_id',
        'ups_id',
        'assigned_to',
        'department',
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }

    public function keyboard()
    {
        return $this->belongsTo(Peripherals::class, 'keyboard_id');
    }

    public function mouse()
    {
        return $this->belongsTo(Peripherals::class, 'mouse_id');
    }

    public function monitor()
    {
        return $this->belongsTo(Peripherals::class, 'monitor_id');
    }

    public function ups()
    {
        return $this->belongsTo(Peripherals::class, 'ups_id');
    }
}
