<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_code')
                    ->label('Employee Code')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->validationMessages([
                        'unique' => 'Employee code is already added.',
                    ]),

                TextInput::make('full_name')
                    ->label('Full Name')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'unique' => 'Employee name is already added.',
                    ]),

                Select::make('department')
                    ->options(function (): array {
                        $departments = [
                            'HR' => 'HR',
                            'Operations' => 'Operations',
                            'MIS' => 'MIS',
                            'Production' => 'Production',
                            'Accounting' => 'Accounting',
                            'Cash' => 'Cash',
                        ];

                        $user = Auth::user();

                        if (! $user || $user->isAdmin() || $user->isAdminLeave()) {
                            return $departments;
                        }

                        $allowedDepartments = array_values(array_filter(
                            $user->departmentRoleAliases(),
                            fn (string $department): bool => array_key_exists($department, $departments),
                        ));

                        $department = $allowedDepartments[0] ?? 'HR';

                        return [$department => $department];
                    })
                    ->default(function (): ?string {
                        $user = Auth::user();

                        if (! $user || $user->isAdmin() || $user->isAdminLeave()) {
                            return null;
                        }

                        $allowedDepartments = array_values(array_filter(
                            $user->departmentRoleAliases(),
                            fn (string $department): bool => in_array($department, ['HR', 'Operations', 'MIS', 'Production', 'Accounting'], true),
                        ));

                        return $allowedDepartments[0] ?? 'HR';
                    })
                    ->disabled(fn (): bool => ! Auth::user()?->isAdmin() && ! Auth::user()?->isAdminLeave())
                    ->dehydrated()
                    ->searchable()
                    ->preload(),

                // TextInput::make('remaining_vl')
                //     ->label('Remaining VL')
                //     ->maxLength(255),

                // TextInput::make('remaining_sl')
                //     ->label('Remaining SL')
                //     ->maxLength(255),

                // TextInput::make('availed_vl')
                //     ->label('Availed VL')
                //     ->maxLength(255),

                // TextInput::make('availed_sl')
                //     ->label('Availed SL')
                //     ->maxLength(255),

                // TextInput::make('availed_wo_pay')
                //     ->label('Availed W/O Pay')
                //     ->maxLength(255),

                // TextInput::make('availed_sss_sl')
                //     ->label('Availed SSS SL')
                //     ->maxLength(255),

                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
