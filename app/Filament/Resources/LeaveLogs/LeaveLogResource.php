<?php

namespace App\Filament\Resources\LeaveLogs;

use App\Filament\Resources\LeaveLogs\Pages\ListLeaveLogs;
use App\Filament\Resources\LeaveLogs\Schemas\LeaveLogForm;
use App\Filament\Resources\LeaveLogs\Tables\LeaveLogsTable;
use App\Models\LeaveLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class LeaveLogResource extends Resource
{
    protected static ?string $model = LeaveLog::class;

    protected static ?string $modelLabel = 'Leave Record';

    protected static ?string $pluralModelLabel = 'Leave Records';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'leave_type';

    protected static UnitEnum|string|null $navigationGroup = 'LEAVE MANAGEMENT';

    protected static ?string $navigationLabel = 'Leave Records';

    public static function form(Schema $schema): Schema
    {
        return LeaveLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin() || $user->isAdminLeave()) {
            return $query;
        }

        $allowedDepartments = $user->departmentRoleAliases();

        if (empty($allowedDepartments)) {
            return $query;
        }

        return $query->whereHas('employee', function (Builder $employeeQuery) use ($allowedDepartments) {
            $employeeQuery->where(function (Builder $q) use ($allowedDepartments) {
                $q->whereIn('department', $allowedDepartments)
                  ->orWhereNull('department');
            });
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaveLogs::route('/'),
        ];
    }
}
