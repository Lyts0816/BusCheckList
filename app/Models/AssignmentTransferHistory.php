<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentTransferHistory extends Model
{
    protected $fillable = [
        'assigned_computer_id',
        'previous_assigned_to',
        'new_assigned_to',
        'previous_department_id',
        'new_department_id',
        'transferred_at',
        'performed_by_id',
        'reason',
        'notes',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(AssignedComputer::class, 'assigned_computer_id');
    }

    public function previousDepartment()
    {
        return $this->belongsTo(Departments::class, 'previous_department_id');
    }

    public function newDepartment()
    {
        return $this->belongsTo(Departments::class, 'new_department_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by_id');
    }
}
