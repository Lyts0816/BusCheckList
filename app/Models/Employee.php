<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'employee_code',
        'full_name',
        'department',
        'remaining_vl',
        'remaining_sl',
        'availed_vl',
        'availed_sl',
        'availed_wo_pay',
        'availed_sss_sl',
        'remarks',
    ];

    public function leaveLogs()
    {
        return $this->hasMany(LeaveLog::class);
    }
}
