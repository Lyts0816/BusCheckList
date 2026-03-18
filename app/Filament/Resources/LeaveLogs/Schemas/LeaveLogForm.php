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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class LeaveLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make()
                    // ->gap(false)
                    ->dense()
                    ->schema([

                        Section::make()
                            ->dense()
                            ->gap(false)
                            ->schema([
                                TextInput::make('control_number')
                                    ->columnSpan(2)
                                    ->label('Control Number')
                                    ->default(fn(): string => LeaveLog::generateNextControlNumber())
                                    ->readOnly()
                                    ->dehydrated()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),

                                DatePicker::make('date_filed')
                                    ->label('Date')
                                    ->default(now())
                                    ->columnSpan(2)
                                    ->required(),

                                Select::make('leave_type')
                                    ->columnSpan(2)
                                    ->options([
                                        'Sick Leave' => 'Sick Leave',
                                        'Vacation Leave' => 'Vacation Leave',
                                        'Emergency Leave' => 'Emergency Leave',
                                        'Maternity Leave' => 'Maternity Leave',
                                        'Paternity Leave' => 'Paternity Leave',
                                        'Other' => 'Other',
                                    ])
                                    ->required(),

                                TextInput::make('company')
                                    // ->required()
                                    ->columnSpan(2)
                                    ->default('Yellow Bus Line Inc.')
                                    ->maxLength(255),
                            ])->columnSpan(2),


                        Section::make()
                            ->dense()
                            ->gap(false)
                            ->schema([
                                Select::make('employee_id')
                                    ->columnSpanFull()
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
                                TextInput::make('relieved_by')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),

                                DatePicker::make('from_date')
                                    ->columnSpanFull()
                                    ->required(),

                                DatePicker::make('to_date')
                                    ->columnSpanFull()
                                    ->required(),
                            ])->columnSpan(3),

                        Section::make()
                            ->dense()
                            ->gap(false)
                            ->schema([
                                TextInput::make('conformed_by')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('conformed_by_position')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('approved_by')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('approved_by_position')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),

                            ])->columnSpan(3),



                        Textarea::make('reason')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('remarks')
                            ->columnSpanFull(),
                    ])->columns(8)->columnSpanFull(),
            ]);
    }
}
