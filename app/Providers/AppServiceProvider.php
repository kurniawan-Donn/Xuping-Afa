<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        Paginator::defaultView('vendor.pagination.afa');

        // Pengaturan website tersedia di semua halaman publik sebagai $settings
        View::composer(['layouts.website', 'website.*', 'components.website.*'], function ($view) {
            $view->with('settings', Setting::allCached());
        });
    }
}
