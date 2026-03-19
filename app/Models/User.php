<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_OPERATIONS = 'operations';
    const ROLE_ADMIN_OPERATIONS = 'admin_operations';

    const ROLE_ADMIN_LEAVE = 'admin_leave';
    const ROLE_USER_HR = 'human_resources_department';
    const ROLE_USER_OPERATIONS_DEPARTMENT = 'operations_department';
    const ROLE_USER_MIS = 'MIS_department';
    const ROLE_USER_PRODUCTION = 'production_department';
    const ROLE_USER_ACCOUNTING = 'accounting_department';
    const ROLE_USER_CASH = 'cash_department';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOperations()
    {
        return $this->role === self::ROLE_OPERATIONS;
    }

    public function isAdminOperations()
    {
        return $this->role === self::ROLE_ADMIN_OPERATIONS;
    }

    public function canViewOperationsDashboard(): bool
    {
        return $this->isAdmin() || $this->isOperations() || $this->role === self::ROLE_ADMIN_OPERATIONS;
    }

    public function canViewLogbookDashboard(): bool
    {
        return $this->isAdmin() || $this->isOperations();
    }

    public function canViewComputersDashboard(): bool
    {
        return $this->isAdmin();
    }

    public function isDepartmentRole(): bool
    {
        return str_ends_with((string) $this->role, '_department');
    }

    //This is for leave module access, which is shared by both admin and admin_operations roles
    public function isAdminLeave()
    {
        return $this->role === self::ROLE_ADMIN_LEAVE;
    }

    public function isHrDepartment()
    {
        return $this->role === self::ROLE_USER_HR;
    }

    public function isOperationsDepartment()
    {
        return $this->role === self::ROLE_USER_OPERATIONS_DEPARTMENT;
    }

    public function isMisDepartment()
    {
        return $this->role === self::ROLE_USER_MIS;
    }

    public function isProductionDepartment()
    {
        return $this->role === self::ROLE_USER_PRODUCTION;
    }

    public function isAccountingDepartment()
    {
        return $this->role === self::ROLE_USER_ACCOUNTING;
    }

    public function isCashDepartment()
    {
        return $this->role === self::ROLE_USER_CASH;
    }

    public function canViewleaveModule(): bool
    {
        return $this->isAdmin() || $this->isHrDepartment() || $this->isOperationsDepartment()
         || $this->isMisDepartment() || $this->isProductionDepartment()
          || $this->isAccountingDepartment() || $this->isCashDepartment();
    }

    public function hasAdminLeaveModule(): bool
    {
        return $this->isAdminLeave();
    }

        public function hasSuperAdminLeaveModule(): bool
    {
        return $this->isAdmin();
    }

    public function canViewEmployeeModule(): bool
    {
        return $this->isAdmin() || $this->isAdminLeave() || $this->isHrDepartment() || $this->isOperationsDepartment()
         || $this->isMisDepartment() || $this->isProductionDepartment()
          || $this->isAccountingDepartment() || $this->isCashDepartment();
    }

    public function hasAdminEmployeeModule(): bool
    {
        return $this->isAdmin() || $this->isAdminLeave();
    }


    /**
     * Return allowed department labels for department-scoped roles.
     * Includes common label variants used across modules.
     *
     * @return array<int, string>
     */
    public function departmentRoleAliases(): array
    {
        return match ($this->role) {
            'human_resources_department' => ['HR', 'Human Resources', 'Human Resources Department'],
            'operations_department' => ['Operations', 'Operations Department'],
            'MIS_department' => ['MIS', 'MIS Department'],
            'production_department' => ['Production', 'Production Department'],
            'accounting_department' => ['Accounting', 'Accounting Department'],
            'cash_department' => ['Cash', 'Cash Department'],
            default => [],
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
