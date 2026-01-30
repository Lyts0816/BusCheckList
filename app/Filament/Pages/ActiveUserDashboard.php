<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use BackedEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActiveUserDashboard extends BaseDashboard
{
	
        public static function canAccess(): bool
    {
        return  Auth::user()->role === User::ROLE_ADMIN;
    }
	protected static string $routePath = '/active-user-dashboard';

	protected static ?string $title = 'Active Users';

	protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

	public function getWidgets(): array
	{
		return [
			\App\Filament\Widgets\ActiveUsersWidget::class,
		];
	}
}
