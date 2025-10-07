<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUnit extends Model
{
    protected $fillable = [
        'asset_code',
        'serial_number',
        'model',
        'date_aquired',
        'OS',
        'windows_serial_number',
        'microsoft_serial_number',
        'ram',
        'storage',
        'processor',
        'ip_address',
        'description',
    ];
}
