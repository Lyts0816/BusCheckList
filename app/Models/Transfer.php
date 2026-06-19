<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{

    protected $table = 'transfers';

    protected $fillable = [
        'date',
        'from',
        'to',
        'prepared_by',
        'guard_on_duty',
        'received_by',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TransferItem::class);
    }
}
