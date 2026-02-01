<?php

namespace App\Providers\Filament;

use App\Filament\Pages\LogbookDashboard;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\ComputersDashboard;
use App\Filament\Pages\OperationsDashboard;
use App\Filament\Widgets\ActiveUsersWidget;
use App\Filament\Pages\ActiveUserDashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->unsavedChangesAlerts()
            ->brandName('MIS SYSTEM')
            ->favicon(asset('images/bus.png'))
            ->spa()
            ->id('admin')
            ->path('admin')
            ->font('Poppins')
            ->sidebarWidth('16rem')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->profile()
            ->globalSearch(false)
            ->passwordReset()
            // ->topbar(false)
            ->userMenuItems([

            ])
            ->navigationGroups([
                'DISPATCH TRIPS',
                'BUS MANAGEMENT',
                'DRIVERS & CONDUCTORS',
            ])
            ->login()
            // ->databaseNotifications()
            ->colors([
                'primary' => Color::Yellow,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
                LogbookDashboard::class,
                ComputersDashboard::class,
                OperationsDashboard::class,
                ActiveUserDashboard::class,
                
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\UpdateLastActivity::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
