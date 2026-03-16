<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveLog extends Model
{
    use HasFactory;

    protected $table = 'leave_logs';

    protected $fillable = [
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
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
