<?php

namespace Trevweb\Cin7CoreClient;

use Exception;
use Generator;

class Cin7CoreClient
{
    private string $accountId;
    private string $applicationKey;
    private string $baseUrl;

    /**
     * @param string $accountId Your Cin7 Core Account ID
     * @param string $applicationKey Your API Application Key
     */
    public function __construct(string $accountId, string $applicationKey, string $baseUrl = 'https://inventory.dearsystems.com/ExternalApi/v2/')
    {
        $this->accountId = $accountId;
        $this->applicationKey = $applicationKey;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Generic method to make API requests.
     *
     * @param string $method HTTP Method (GET, POST, PUT, DELETE)
     * @param string $endpoint The API endpoint (e.g., 'Product', 'SaleList')
     * @param array $params Query string parameters
     * @param mixed|null $data JSON body data for POST/PUT
     * @return mixed           Decoded JSON response
     * @throws Exception       If the API request fails
     */
    public function call(string $method, string $endpoint, array $params = [], mixed $data = null): mixed
    {
        // Clean endpoint
        $url = $this->baseUrl . ltrim($endpoint, '/');

        // Append query parameters if present
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->executeRequest($method, $url, $data);
    }

    /**
     * Executes the actual HTTP request.
     * This is protected so it can be overridden in tests.
     */
    protected function executeRequest(string $method, string $url, mixed $data = null): mixed
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        // Prepare Headers [cite: 3911]
        $headers = [
            'Content-Type: application/json',
            'api-auth-accountid: ' . $this->accountId,
            'api-auth-applicationkey: ' . $this->applicationKey
        ];

        // Add body data if applicable
        if ($data !== null && in_array(strtoupper($method), ['POST', 'PUT'])) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $headers[] = 'Content-Length: ' . strlen($jsonData);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("cURL Error: $error");
        }

        // Handle API Status Codes [cite: 1945]
        if ($httpCode >= 400) {
            throw new Exception("API Error ($httpCode): " . $response);
        }

        return json_decode($response, true);
    }

    /**
     * Helper for GET requests
     */
    public function get($endpoint, $params = [])
    {
        return $this->call('GET', $endpoint, $params);
    }

    /**
     * Helper for POST requests
     */
    public function post($endpoint, $data)
    {
        return $this->call('POST', $endpoint, [], $data);
    }

    /**
     * Helper for PUT requests
     */
    public function put($endpoint, $data)
    {
        return $this->call('PUT', $endpoint, [], $data);
    }

    /**
     * Helper for DELETE requests
     */
    public function delete($endpoint, $params = [])
    {
        return $this->call('DELETE', $endpoint, $params);
    }

    /**
     * Automatically fetches all pages for paginated endpoints.
     * * Pagination uses 'Page' and 'Limit' parameters. The response contains a 'Total'
     * field indicating the total number of records. [cite: 1943, 1944]
     *
     * @param string $endpoint The API endpoint (e.g., 'Product')
     * @param string $listKey The key in the response containing the list (e.g., 'Products', 'SaleList')
     * @param array $params Additional filter parameters
     * @return Generator       Yields individual items from all pages
     */
    public function fetchAll(string $endpoint, string $listKey, array $params = []): Generator
    {
        $page = 1;
        $limit = 100; // Default page size [cite: 1943]
        $params['Limit'] = $limit;

        do {
            $params['Page'] = $page;

            // Fetch the current page
            $response = $this->get($endpoint, $params);

            // Check if the expected list key exists
            if (!isset($response[$listKey]) || !is_array($response[$listKey])) {
                break;
            }

            // Yield items from the current page
            foreach ($response[$listKey] as $item) {
                yield $item;
            }

            // Check Total to determine if we need to fetch more
            // If the total processed so far is less than 'Total', continue.
            // Alternatively, simply check if the current page returned fewer items than the limit.
            $total = $response['Total'] ?? 0;
            $fetchedCount = count($response[$listKey]);

            // Optimization: If we fetched fewer items than the limit, we are done.
            if ($fetchedCount < $limit) {
                break;
            }

            // Also stop if we have exceeded the reported Total (safety check)
            if (($page * $limit) >= $total && $total > 0) {
                break;
            }

            $page++;

        } while (true);
    }
}