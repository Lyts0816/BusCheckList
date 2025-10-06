<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peripherals extends Model
{
    protected $table = 'peripherals';

    protected $fillable = [
        'user',
        'department',
        'item_type',
        'model',
        'serial_number',
        'asset_code',
        'date_acquired',
        'status',
        'description',
    ];
}
