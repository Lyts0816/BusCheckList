<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsChecklist extends Model
{
    protected $table = 'items_checklist';

    protected $fillable = [
        'item_type',
        'item_model',
        'bus_id',
        'item_asset_code',
        'status',
        'date_checked',
        'remarks'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}
