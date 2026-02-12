<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispatchedTrips extends Model
{
    use HasFactory;

    protected $table = 'dispatched_trips';

    protected $fillable = [
        'bus_number_id',
        'dispatch_sheet_id',
        'trip_number',
        'nature_of_trip_id',
        'driver_id',
        'conductor_id',
        'time_in_terminal',
        'time_of_parking',
        'time_of_departure',
        'time_of_arrival',
        'idle_time_start',
        'idle_time_end',
        'total_travel_time_minutes',
        'total_add_time_minutes',
        'ticket_number',
        'passengers_on_board',
        'baggage_amount',
        'baggage_ticket_no',
        'remarks',
        'snap_drivers',
        'snap_conductors',
    ];

    protected $casts = [
        'time_in_terminal' => 'datetime:H:i',
        'time_of_parking' => 'datetime:H:i',
        'time_of_departure' => 'datetime:H:i',
        'time_of_arrival' => 'datetime:H:i',
        'idle_time_start' => 'datetime:H:i',
        'idle_time_end' => 'datetime:H:i',
    ];

    // Relationships
    public function natureOfTrip()
    {
        return $this->belongsTo(NatureOfTrip::class, 'nature_of_trip_id');
    }

    public function busNumber()
    {
        return $this->belongsTo(BusNumber::class, 'bus_number_id');
    }

    public function dispatchSheet()
    {
        return $this->belongsTo(DispatchSheet::class);
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
