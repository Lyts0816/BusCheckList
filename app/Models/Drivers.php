<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DispatchedTrips;

class Drivers extends Model
{
    use HasFactory;
    
    protected $table = 'drivers';

    protected $fillable = [
        'driver_name',
        'status',
        'remarks',
    ];

    // Relationships
    public function dispatchedTrips()
    {
        return $this->hasMany(DispatchedTrips::class, 'driver_id');
    }
}
