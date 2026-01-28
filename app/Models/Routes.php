<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Routes extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = [
        'from',
        'to',
        'distance',
        'remarks',
    ];

    // Relationships
    public function dispatchedTrips()
    {
        return $this->hasMany(DispatchedTrips::class, 'route_id');
    }
}
