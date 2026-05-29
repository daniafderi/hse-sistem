<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\DailySafetyPatrol;

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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();

            $notifications = $user
                ->notifications()
                ->with('notifiable')
                ->latest('notifications.created_at')
                ->limit(5)
                ->get();

            $unreadCount = $user
                ->notifications()
                ->wherePivot('is_read', false)
                ->count();

            $view->with([
                'globalNotifications' => $notifications,
                'unreadNotifCount' => $unreadCount,
            ]);
        });

        Gate::define('isSupervisor', function ($user) {
            return $user->role == 'Supervisor';
        });
        Gate::define('isHseKantor', function ($user) {
            return $user->role == 'HSE Kantor';
        });
        Gate::define('isHseLapangan', function ($user) {
            return $user->role == 'HSE Lapangan';
        });
        Gate::define('isHseAdmin', function ($user) {
            return $user->role === 'HSE Admin';
        });
        Gate::define(
            'tambah-project',
            fn($user) =>
            in_array($user->role, ['Supervisor', 'HSE Lapangan'])
        );
        Gate::define('manage-report', function($user, DailySafetyPatrol $dailySafetyPatrol) {
            return $dailySafetyPatrol->users()
            ->where('user_id', $user->id)
            ->exists();
        });
    }
}
