<?php

namespace App\Filament\Resources\LeaveLogs\Schemas;

use App\Models\Employee;
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
        return $schema
            ->components([
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
                    ->required()
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
