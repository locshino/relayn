<?php

namespace App\Providers;

use App\Models\ApiConnection;
use App\Observers\ApiConnectionObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ApiConnection::observe(ApiConnectionObserver::class);
    }
}
