<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActiveUsersWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $minutes = 1;

        $user = Auth::user();

        $query = User::where('last_activity_at', '>=', now()->subMinutes($minutes));

        // Non-admins only see their department
        if ($user && $user->role !== 'admin') {
            $query->where('role', $user->role);
        }

        return [
            Stat::make(
                'Admins Active',
                User::where('role', 'admin')
                    ->where('last_activity_at', '>=', now()->subMinutes(5))
                    ->count()
            ),
            Stat::make(
                'Operations Active',
                User::where('role', 'operations')
                    ->where('last_activity_at', '>=', now()->subMinutes(5))
                    ->count()
            ),
        ];
    }
}
