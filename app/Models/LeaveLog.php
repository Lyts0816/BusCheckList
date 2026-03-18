<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveLog extends Model
{
    use HasFactory;

    protected const DEPARTMENT_LABELS = ['HR', 'Operations', 'MIS', 'Production', 'Accounting', 'Cash'];

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

    protected $casts = [
        'date_filed' => 'date',
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (LeaveLog $leaveLog): void {
            $leaveLog->control_number = self::generateNextControlNumber();
        });
    }

    public static function generateNextControlNumber(): string
    {
        $user = Auth::user();

        $allowedDepartments = $user
            ? array_values(array_filter(
                $user->departmentRoleAliases(),
                fn(string $department): bool => in_array($department, self::DEPARTMENT_LABELS, true),
            ))
            : [];

        $department = $allowedDepartments[0] ?? 'HR';
        $departmentCode = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $department));
        $prefix = $departmentCode . now()->format('ym');

        $maxSequence = self::query()
            ->where('control_number', 'like', $prefix . '%')
            ->pluck('control_number')
            ->map(function (string $controlNumber) use ($prefix): int {
                $sequencePart = substr($controlNumber, strlen($prefix));

                return ctype_digit($sequencePart) ? (int) $sequencePart : 0;
            })
            ->max() ?? 0;

        $nextSequence = str_pad((string) ($maxSequence + 1), 2, '0', STR_PAD_LEFT);

        return $prefix . $nextSequence;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
