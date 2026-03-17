<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                        'operations' => 'Operations',
                        'admin_operations' => 'Admin Operations',

                        'human_resources_department' => 'Human Resources Department',
                        'operations_department' => 'Operations Department',
                        'MIS_department' => 'MIS Department',
                        'production_department' => 'Production Department',
                        'accounting_department' => 'Accounting Department',
                        'admin_leave' => 'Admin Leave',
                        'cash_department' => 'Cash Department',
                    ])
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
