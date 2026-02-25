<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        /**
         * Força o esquema HTTPS em produção.
         * Isso garante que as URLs geradas pelo route() e action() no Blade
         * usem https:// e não causem erro de Mixed Content.
         */
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
