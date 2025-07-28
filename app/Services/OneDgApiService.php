<?php

namespace App\Services;

use App\Enums\ApiConnectionEnum;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Service class for interacting with the 1DG API.
 * https://1dg.me/api/v2
 */
class OneDgApiService extends BaseApiService
{
    /**
     * Set the name of the API connection.
     */
    public function connectionName(): string
    {
        return ApiConnectionEnum::ONEDG->value;
    }

    /**
     * Builds the base request for the 1DG API.
     * It uses the POST method and form-urlencoded content type.
     */
    protected function buildRequest(): PendingRequest
    {
        return Http::baseUrl($this->connection->api_url)
            ->asForm(); // Use application/x-www-form-urlencoded
    }

    /**
     * Executes a POST request with common parameters.
     *
     * @param  string  $action  The API action to perform.
     * @param  array  $params  Additional parameters for the request.
     * @return array|null The JSON response as an array.
     */
    private function post(string $action, array $params = []): ?array
    {
        $payload = array_merge($params, [
            'key' => $this->connection->api_key,
            'action' => $action,
        ]);

        return $this->buildRequest()->post('', $payload)->json();
    }

    // --- Service & Order Actions ---

    /**
     * 1. Get the list of available services.
     */
    public function getServices(): ?array
    {
        return $this->post('services');
    }

    /**
     * 2. Add a new order.
     *
     * @param  array  $optionalParams  Optional data like 'comments', 'list', 'suggest', 'search'.
     */
    public function addOrder(int|string $serviceId, string $link, int $quantity, array $optionalParams = []): ?array
    {
        $data = [
            'service' => $serviceId,
            'link' => $link,
            'quantity' => $quantity,
        ];

        return $this->post('add', array_merge($data, $optionalParams));
    }

    /**
     * 3. Get the status of a single order.
     */
    public function getOrderStatus(int|string $orderId): ?array
    {
        return $this->post('status', ['order' => $orderId]);
    }

    /**
     * 4. Get the status of multiple orders.
     *
     * @param  array  $orderIds  An array of order IDs. (Limit 100)
     */
    public function getMultipleOrderStatuses(array $orderIds): ?array
    {
        $orderIdString = implode(',', array_slice($orderIds, 0, 100));

        return $this->post('status', ['orders' => $orderIdString]);
    }

    // --- Refill Actions ---

    /**
     * 5. Create a refill for a single order.
     */
    public function createRefill(int|string $orderId): ?array
    {
        return $this->post('refill', ['order' => $orderId]);
    }

    /**
     * 6. Create refills for multiple orders.
     *
     * @param  array  $orderIds  An array of order IDs. (Limit 100)
     */
    public function createMultipleRefills(array $orderIds): ?array
    {
        $orderIdString = implode(',', array_slice($orderIds, 0, 100));

        return $this->post('refill', ['orders' => $orderIdString]);
    }

    /**
     * 7. Get the status of a single refill.
     */
    public function getRefillStatus(int|string $refillId): ?array
    {
        return $this->post('refill_status', ['refill' => $refillId]);
    }

    /**
     * 8. Get the status of multiple refills.
     *
     * @param  array  $refillIds  An array of refill IDs. (Limit 100)
     */
    public function getMultipleRefillStatuses(array $refillIds): ?array
    {
        $refillIdString = implode(',', array_slice($refillIds, 0, 100));

        return $this->post('refill_status', ['refills' => $refillIdString]);
    }

    // --- Product Actions ---

    /**
     * 9. Get the list of available products.
     */
    public function getProducts(): ?array
    {
        return $this->post('products');
    }

    /**
     * 10. Add a new product order.
     *
     * @param  string|null  $requiredInfo  Optional information needed for the product.
     */
    public function addProductOrder(int|string $productId, int $quantity, ?string $requiredInfo = null): ?array
    {
        $data = [
            'product' => $productId,
            'quantity' => $quantity,
        ];

        if ($requiredInfo) {
            $data['require'] = $requiredInfo;
        }

        return $this->post('add_product_order', $data);
    }

    /**
     * 11. Get the status of a single product order.
     */
    public function getProductOrderStatus(int|string $orderId): ?array
    {
        return $this->post('product_order_status', ['order' => $orderId]);
    }

    /**
     * 12. Get the status of multiple product orders.
     *
     * @param  array  $orderIds  An array of product order IDs. (Limit 100)
     */
    public function getMultipleProductOrderStatuses(array $orderIds): ?array
    {
        $orderIdString = implode(',', array_slice($orderIds, 0, 100));

        return $this->post('product_order_status', ['orders' => $orderIdString]);
    }

    /**
     * 13. Get the result of a product order.
     */
    public function getProductResult(int|string $orderId): ?array
    {
        return $this->post('result_product', ['order' => $orderId]);
    }

    // --- Account Actions ---

    /**
     * 14. Get the current account balance.
     */
    public function getBalance(): ?array
    {
        return $this->post('balance');
    }
}
