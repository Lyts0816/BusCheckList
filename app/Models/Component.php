<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'asset_type',
        'name',
        'description',
    ];

    public function maintenanceLogs()
    {
        return $this->hasMany(AssetMaintenanceLog::class);
    }
}
