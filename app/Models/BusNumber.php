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
        'bus_type',
        'seat_capacity',
    ];
}
