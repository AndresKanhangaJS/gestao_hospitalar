<?php

namespace App\Providers;
use Hashids\Hashids;

use Illuminate\Support\ServiceProvider;

class HashidsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Hashids::class, function () {
            return new Hashids(
                config('hashids.salt'),
                config('hashids.length'),
                config('hashids.alphabet')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
