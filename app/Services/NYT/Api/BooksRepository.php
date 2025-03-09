<?php

namespace App\Services\NYT\Api;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

readonly class BooksRepository implements BooksRepositoryInterface
{
    private PendingRequest $http;

    public function __construct()
    {
        $baseUrl = config('nytimes.base_url');
        $endpoint = config('nytimes.api.books.endpoint');
        $apiKey = config('nytimes.api.books.key');

        $this->http = Http::baseUrl("$baseUrl/$endpoint")->withQueryParameters([
            'api-key' => $apiKey,
        ]);
    }

    /**
     * Get the history of Best Sellers lists.
     *
     * @param array $queryParams Query parameters for filtering and pagination.
     * @return array
     * @throws Exception
     */
    public function getBestSellersHistory(array $queryParams = []): array
    {
        $response = $this->http->get('/lists/best-sellers/history.json', $queryParams);

        if ($response->failed()) {
            throw new Exception('Failed to fetch best sellers history from NYT API.');
        }

        return $response->json();
    }

}
