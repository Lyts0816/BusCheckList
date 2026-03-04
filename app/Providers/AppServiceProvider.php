<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            Event::listen(Logout::class, function ($event) {
            $user = $event->user;
            if ($user) {
                $user->updateQuietly(['last_activity_at' => null]);
            }
        });
    }
}
