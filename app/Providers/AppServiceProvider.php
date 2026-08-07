<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        $this->bootWithViewShare();
    }

    public function bootWithViewShare()
    {
        try {
            $setting = Setting::first();
            $systemSetting = SystemSetting::with('currency')->first();
            View::share('setting', $setting);
            View::share('system_setting', $systemSetting);
        } catch (\Exception $e) {
            // Handle the exception or log it
        }
    }
}
