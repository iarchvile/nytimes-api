<?php

namespace App\Services\NYT\Api;

use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use Exception;

readonly class BooksService
{

    public function __construct(
        private BooksRepositoryInterface $repository,
        private ApiRateLimiterInterface  $rateLimiter
    )
    {

    }

    /**
     * @throws Exception
     */
    public function getBestSellersHistory(string $requestCacheKey, array $queryParams): array
    {
        $response = $this->repository->getBestSellersHistory($queryParams);

        $this->rateLimiter->hitPerDayRateLimit();
        $this->rateLimiter->hitPerMinuteRateLimit();
        $this->rateLimiter->cacheResponse($requestCacheKey, $response);

        return $response;
    }
}
