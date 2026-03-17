<?php

namespace App\Filament\Resources\LeaveLogs\Schemas;

use App\Models\Employee;
use App\Models\LeaveLog;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LeaveLogForm
{
    public static function configure(Schema $schema): Schema
    {
        $departmentLabels = ['HR', 'Operations', 'MIS', 'Production', 'Accounting', 'Cash'];

        $generateControlNumber = function () use ($departmentLabels): string {
            $user = Auth::user();

            $allowedDepartments = $user
                ? array_values(array_filter(
                    $user->departmentRoleAliases(),
                    fn (string $department): bool => in_array($department, $departmentLabels, true),
                ))
                : [];

            $department = $allowedDepartments[0] ?? 'HR';
            $departmentCode = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $department));
            $prefix = $departmentCode . now()->format('ym');

            $maxSequence = LeaveLog::query()
                ->where('control_number', 'like', $prefix . '%')
                ->pluck('control_number')
                ->map(function (string $controlNumber) use ($prefix): int {
                    $sequencePart = substr($controlNumber, strlen($prefix));

                    return ctype_digit($sequencePart) ? (int) $sequencePart : 0;
                })
                ->max() ?? 0;

            $nextSequence = str_pad((string) ($maxSequence + 1), 2, '0', STR_PAD_LEFT);

            return $prefix . $nextSequence;
        };

        return $schema
            ->components([
                TextInput::make('control_number')
                    ->label('Control Number')
                    ->default($generateControlNumber)
                    ->readOnly()
                    ->dehydrated()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                Select::make('employee_id')
                    ->label('Employee')
                    ->options(function () {
                        $query = Employee::query()->orderBy('full_name');
                        $user = Auth::user();

                        if ($user && ! ($user->isAdmin() || $user->isAdminOperations())) {
                            $allowedDepartments = $user->departmentRoleAliases();

                            if (! empty($allowedDepartments)) {
                                $query->whereIn('department', $allowedDepartments);
                            }
                        }

                        return $query->pluck('full_name', 'id');
                    })
                    ->searchable()
                    ->required(),

                Select::make('leave_type')
                    ->options([
                        'sick' => 'Sick Leave',
                        'vacation' => 'Vacation Leave',
                        'emergency' => 'Emergency Leave',
                        'maternity' => 'Maternity Leave',
                        'paternity' => 'Paternity Leave',
                        'other' => 'Other',
                    ])
                    ->required(),

                TextInput::make('company')
                    // ->required()
                    ->default('Yellow Bus Line Inc.')
                    ->maxLength(255),

                DatePicker::make('from_date')
                    ->required(),

                DatePicker::make('to_date')
                    ->required(),

                TextInput::make('relieved_by')
                    ->required()
                    ->maxLength(255),

                TextInput::make('conformed_by')
                    ->required()
                    ->maxLength(255),

                TextInput::make('approved_by')
                    ->maxLength(255),

                Textarea::make('reason')
                    ->columnSpanFull(),

                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
