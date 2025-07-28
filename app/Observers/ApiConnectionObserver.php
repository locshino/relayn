<?php

namespace App\Observers;

use App\Models\ApiConnection;
use Illuminate\Support\Facades\Cache;

class ApiConnectionObserver
{
    /**
     * Handle the ApiConnection "created" event.
     */
    public function created(ApiConnection $apiConnection): void
    {
        $this->clearCache();
    }

    /**
     * Handle the ApiConnection "updated" event.
     */
    public function updated(ApiConnection $apiConnection): void
    {
        $this->clearCache();
    }

    /**
     * Handle the ApiConnection "deleted" event.
     */
    public function deleted(ApiConnection $apiConnection): void
    {
        $this->clearCache();
    }

    /**
     * Clear the relevant cache key.
     */
    protected function clearCache(): void
    {
        $apiExists = [
            'onedg_api_exists',
        ];

        foreach ($apiExists as $key) {
            Cache::forget($key);
        }
    }
}
