<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peripherals extends Model
{
    protected $table = 'peripherals';

    protected $fillable = [
        'item_type',
        'asset_code',
        'serial_number',
        'model',
        'date_acquired',
        'description',
    ];
}
