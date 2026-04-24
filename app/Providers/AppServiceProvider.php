<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

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
        View::composer('*', function ($view) {

            if (Auth::check()) {

                $unreadCount = Notifikasi::where('id_pengguna', Auth::user()->id_pengguna)
                    ->where('status_baca', false)
                    ->whereNull('deleted_at')
                    ->count();

                $view->with('globalUnreadNotif', $unreadCount);
            }
        });
    }
}
