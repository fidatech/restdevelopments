<?php

namespace App\Providers;

use App\Models\ContactSetting;
use Illuminate\Support\Facades\URL;
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
        // The hosting sends every request into the public folder, so Laravel would put
        // "/public" in every link and image address. Links use the domain itself instead.
        if (! $this->app->runningInConsole()) {
            $request = $this->app['request'];
            URL::forceRootUrl($request->getSchemeAndHttpHost());
            if ($request->isSecure()) {
                URL::forceScheme('https');
            }
        }

        View::composer('*', function ($view) {
        $setting = ContactSetting::with('translations')->first();
        $view->with('globalContactSetting', $setting);
    });
    }
}
