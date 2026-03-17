<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveLog extends Model
{
    use HasFactory;

    protected $table = 'leave_logs';

    protected $fillable = [
        'date_filed',
        'control_number',
        'employee_id',
        'company',
        'leave_type',
        'from_date',
        'to_date',
        'relieved_by',
        'conformed_by',
        'approved_by',
        'reason',
        'remarks',
        'conformed_by_position',
        'approved_by_position',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
