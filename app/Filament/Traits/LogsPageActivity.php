<?php

// app/Filament/Traits/LogsPageActivity.php

namespace App\Filament\Traits;

trait LogsPageActivity
{
    /**
     * Helper function to log a user activity.
     *
     * @param  string  $description  A human-readable description of the action.
     * @param  array  $properties  Additional context data to store with the log.
     */
    protected function logActivity(string $description, array $properties = []): void
    {
        activity()
            ->causedBy(auth()->user()) // The user who performed the action.
            ->withProperties($properties) // Store extra data as JSON.
            ->log($description);
    }
}
