<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

use BackedEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use UnitEnum;

class OperationsDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected bool $isCollapsible = true;
    protected static bool $isLazy = false;
    // ...
    protected static string $routePath = '/operations-dashboard';

    protected static ?string $title = 'Dispatch Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'DISPATCH TRIPS';

    public static function canAccess(): bool
    {
        return  Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_OPERATIONS || Auth::user()->role === User::ROLE_ADMIN_OPERATIONS;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->default(now()->startOfMonth())
                    ->maxDate(fn ($get) => $get('end_date') ?: now())
                    ->native(false),
                DatePicker::make('end_date')
                    ->label('End Date')
                    ->default(now())
                    ->minDate(fn ($get) => $get('start_date'))
                    ->maxDate(now())
                    ->native(false),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\OperationsChecklistStats::class,
            \App\Filament\Widgets\OperationsTripsStats::class,
            \App\Filament\Widgets\BusStatusWidget::class,
            \App\Filament\Widgets\RoutePopularityWidget::class,
            \App\Filament\Widgets\TripTypeWidget::class,
        ];
    }
}
