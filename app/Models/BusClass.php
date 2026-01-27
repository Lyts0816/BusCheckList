<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class BusClass extends Model
{
    use HasFactory;
    
    protected $table = 'bus_classes';

    protected $fillable = [
        'class_name',
        'description',
        'remarks',
    ];

    // Relationships
    public function dispatchedTrips()
    {
        return $this->hasMany(DispatchedTrip::class, 'bus_class_id');
    }
}
