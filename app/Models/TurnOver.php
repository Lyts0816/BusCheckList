<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurnOver extends Model
{
    protected $table = 'turn_over';
    
    protected $fillable = [
        'from_department',
        'to_department',
        'current_date',
        'printed_date',
        'quantity',
        'particulars',
        'serial_number',
        'recipient',
        'recipient_department_head',
        'endorser',
        'endorser_department_head',
    ];
}
