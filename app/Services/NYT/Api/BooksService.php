<?php

namespace App\Services\NYT\Api;

use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $this->cacheResponse($requestCacheKey, $response);

        return $response;
    }

    public function getRequestKey(Request $request): string
    {
        return ApiRateLimiterInterface::LIMIT_MINUTE_KEY . md5(serialize($request->all()));
    }

    public function cacheResponse(string $requestCacheKey, array $response): void
    {
        Cache::put($requestCacheKey, $response, now()->addSeconds($this->rateLimiter->decaySeconds()));
    }
}
