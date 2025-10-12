<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $table = 'printers';

    protected $fillable = [
        'department',
        'printer_host',
        'printer_model',
        'printer_asset_code',
        'printer_serial_number',
        'date_aquired',
        'description',
    ];
}
