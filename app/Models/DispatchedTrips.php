<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispatchedTrips extends Model
{
    use HasFactory;

    protected $table = 'dispatched_trips';

    protected $fillable = [
        'trip_number',
        'route_id',
        'bus_number_id',
        'bus_class_id',
        'nature_of_trip_id',
        'driver_id',
        'conductor_id',
        'date_time_in_terminal',
        'date_time_of_parking',
        'date_time_of_departure',
        'date_time_of_arrival',
        'idle_time_start',
        'idle_time_end',
        'total_travel_time_minutes',
        'total_add_time_minutes',
        'km_run',
        'ticket_number',
        'passengers_on_board',
        'baggage_amount',
        'baggage_ticket_no',
        'remarks',
    ];

    protected $casts = [
        'date_time_in_terminal' => 'datetime',
        'date_time_of_parking' => 'datetime',
        'date_time_of_departure' => 'datetime',
        'date_time_of_arrival' => 'datetime',
        'idle_time_start' => 'datetime',
        'idle_time_end' => 'datetime',
    ];

    // Relationships
    public function route()
    {
        return $this->belongsTo(Routes::class, 'route_id');
    }

    public function busNumber()
    {
        return $this->belongsTo(BusNumber::class, 'bus_number_id');
    }

    public function busClass()
    {
        return $this->belongsTo(BusClass::class, 'bus_class_id');
    }

    public function natureOfTrip()
    {
        return $this->belongsTo(NatureOfTrip::class, 'nature_of_trip_id');
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
