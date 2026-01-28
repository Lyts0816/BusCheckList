<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NatureOfTrip extends Model
{
    use HasFactory;
    
    protected $table = 'nature_of_trips';

    protected $fillable = [
        'nature_of_trip_name',
        'description',
        'remarks',
    ];

    // Relationships
    public function dispatchedTrips()
    {
        return $this->hasMany(DispatchedTrips::class, 'nature_of_trip_id');
    }
}
