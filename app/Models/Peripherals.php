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
    ];

    public function assignedKeyboards()
    {
        return $this->hasMany(AssignedComputer::class, 'keyboard_id');
    }
    public function assignedMice()
    {
        return $this->hasMany(AssignedComputer::class, 'mouse_id');
    }
    public function assignedMonitors(){
        return $this->hasMany(AssignedComputer::class, 'monitor_id');
    }
    public function assignedUps(){
        return $this->hasMany(AssignedComputer::class, 'ups_id');
    }
}
