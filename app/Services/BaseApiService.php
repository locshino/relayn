<?php

// app/Services/BaseApiService.php

namespace App\Services;

use App\Models\ApiConnection;
use Illuminate\Http\Client\PendingRequest;
use RuntimeException;

abstract class BaseApiService
{
    protected ApiConnection $connection;

    protected string $connectionName;

    /**
     * The constructor finds the active connection by its name.
     */
    public function __construct()
    {
        $this->setConnectionName();
        $this->setConnection();
    }

    /**
     * Set the name of the API connection.
     */
    public function setConnection(): void
    {
        $connectionName = $this->getConnectionName();
        $connection = ApiConnection::where('name', $connectionName)
            ->where('is_active', true)
            ->first();

        if (! $connection) {
            throw new RuntimeException("Active API connection '{$connectionName}' not found.");
        }

        $this->connection = $connection;
    }

    abstract public function connectionName(): string;

    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    public function setConnectionName(): void
    {
        $this->connectionName = $this->connectionName();
    }

    abstract protected function buildRequest(): PendingRequest;
}
