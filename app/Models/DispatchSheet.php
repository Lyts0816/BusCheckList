<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Routes;

class DispatchSheet extends Model
{
    use HasFactory;

    protected $table = 'dispatch_sheets';

    protected $fillable = [
        'dispatch_date',
        'route_id',
        'bus_number_id',
        'origin',
        'destination',
        'distance_at_dispatch',
    ];

    public function trips()
    {
        return $this->hasMany(DispatchedTrips::class);
    }

    public function route()
    {
        return $this->belongsTo(Routes::class);
    }

    public function busNumber()
    {
        return $this->belongsTo(BusNumber::class, 'bus_number_id');
    }

    protected static function booted()
    {
        static::creating(function ($dispatch) {
            if ($dispatch->route_id) {
                $route = Routes::find($dispatch->route_id);
                if ($route) {
                    $dispatch->origin = $route->from;
                    $dispatch->destination = $route->to;
                    $dispatch->distance_at_dispatch = $route->distance;
                }
            }
        });
    }


    protected $casts = [
        'dispatch_date' => 'date',
    ];
}
