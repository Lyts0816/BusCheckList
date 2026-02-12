<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusNumber extends Model
{

    use HasFactory;

    protected $table = 'bus_numbers';

    protected $fillable = [
        'bus_number',
        'bus_model',
        'bus_class',
        'seat_capacity',
        'driver_id',
        'conductor_id',
    ];

    // Relationships
    public function dispatchedTrips()
    {
        return $this->hasMany(DispatchedTrips::class, 'bus_number_id');
    }

    public function dispatchSheets()
    {
        return $this->hasMany(DispatchSheet::class, 'bus_number_id');
    }

    public function driver()
    {
        return $this->belongsTo(Drivers::class, 'driver_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductors::class, 'conductor_id');
    }
}
