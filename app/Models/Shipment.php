<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{

    protected $table = 'shipments';
    
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'barcode',
        'or_number',

        'origin_terminal',
        'destination_terminal',

        'sender_name',
        'sender_address',
        'sender_contact',

        'recipient_name',
        'recipient_address',
        'recipient_contact',

        'box_number',
        'description',
        'quantity',
        'weight',

        'status',

        'shipped_at',
        'arrived_at',
        'claimed_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'weight' => 'decimal:2',

        'shipped_at' => 'datetime',
        'arrived_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];


}
