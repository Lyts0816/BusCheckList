<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conductors extends Model
{
    use HasFactory;

    protected $table = 'conductors';

    protected $fillable = [
        'conductor_name',
        'status',
        'remarks',
    ];

    // Relationships
    public function dispatchedTrips()
    {
        return $this->hasMany(DispatchedTrips::class, 'conductor_id');
    }
}
