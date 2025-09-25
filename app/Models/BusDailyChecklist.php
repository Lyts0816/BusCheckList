<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusDailyChecklist extends Model
{
    protected $table = 'bus_daily_checklists';

    protected $fillable = [
        'bus_id',
        'check_date',
        'checked',
        'remarks',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
