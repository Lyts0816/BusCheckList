<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispatchTrip extends Model
{
    use HasFactory;

    protected $table = 'dispatch_trips';

    protected $fillable = [
        'trip_number',
        'from',
        'to',
        'bus_number',
        'bus_class',
        'nature_of_trip',
        'date_time_in_terminal',
        'date_time_of_parking',
        'date_time_of_departure',
        'date_time_of_arrival',
        'idle_time_start',
        'idle_time_end',
        'driver',
        'conductor',
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
        'date_time_in_terminal'   => 'datetime',
        'date_time_of_parking'    => 'datetime',
        'date_time_of_departure'  => 'datetime',
        'date_time_of_arrival'    => 'datetime',
        'idle_time_start'         => 'datetime:H:i', // stored as time
        'idle_time_end'           => 'datetime:H:i',
        'total_travel_time_minutes' => 'integer',
        'total_add_time_minutes'    => 'integer',
        'km_run'                    => 'integer',
        'ticket_number'             => 'integer',
        'passengers_on_board'       => 'integer',
        'baggage_amount'            => 'integer',
        'baggage_ticket_no'         => 'integer',
    ];

    public function getTravelTimeHoursAttribute()
    {
        return intdiv($this->total_travel_time_minutes, 60);
    }

    public function getTravelTimeMinutesAttribute()
    {
        return $this->total_travel_time_minutes % 60;
    }

    /**
     * Optionally, accessor for add time
     */
    public function getAddTimeHoursAttribute()
    {
        return intdiv($this->total_add_time_minutes, 60);
    }

    public function getAddTimeMinutesAttribute()
    {
        return $this->total_add_time_minutes % 60;
    }

    /**
     * Optionally, calculate total travel time automatically
     * from departure and arrival if needed
     */
    public function calculateTotalTravelTime()
    {
        if ($this->date_time_of_departure && $this->date_time_of_arrival) {
            $diff = $this->date_time_of_departure->diffInMinutes($this->date_time_of_arrival);
            $this->total_travel_time_minutes = $diff;
        }
    }
}
