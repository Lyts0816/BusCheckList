<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{

    protected $table = 'transfer_items';

    protected $fillable = [
        'transfer_id',
        'item_name',
        'asset_code',
        'serial_number',
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
