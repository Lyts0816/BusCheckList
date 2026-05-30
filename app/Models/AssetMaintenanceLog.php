<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceLog extends Model
{
    protected $fillable = [
        'maintainable_type',
        'maintainable_id',
        'component_id',
        'office_supply_id',
        'maintenance_type',
        'maintenance_date',
        'performed_by',
        'issue_reported',
        'action_taken',
        'cost',
        'next_maintenance',
        'remarks',
    ];

    public function maintainable()
    {
        return $this->morphTo();
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function officeSupply()
    {
        return $this->belongsTo(OfficeSupplies::class);
    }
}
