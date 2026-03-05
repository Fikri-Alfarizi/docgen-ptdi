<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

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
        try {
            $settings = Cache::remember('system_settings_all', 3600, function () {
                return SystemSetting::all()->pluck('value', 'key')->toArray();
            });
            View::share('site_settings', $settings);
        } catch (\Exception $e) {
            View::share('site_settings', []);
        }
    }
}
