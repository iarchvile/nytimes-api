<?php

namespace App\Http\Api\V1\Controllers;

use App\Facades\ApiRateLimiter;
use App\Http\Api\V1\Requests\BestSellersHistoryRequest;
use App\Http\Controllers\Controller;
use App\Services\NYT\Api\BooksService;
use Exception;
use Illuminate\Support\Facades\Cache;

class BestSellersController extends Controller
{
    /**
     * @throws Exception
     */
    public function history(BestSellersHistoryRequest $request, BooksService $service)
    {
        $requestCacheKey = $service->getRequestKey($request);

        if (Cache::has($requestCacheKey)) {
            return Cache::get($requestCacheKey);
        }

        if (ApiRateLimiter::isAttemptsPerMinuteExceeded()) {
            return ApiRateLimiter::tooManyAttempts();
        }

        return $service->getBestSellersHistory($requestCacheKey, $request->validated());
    }

}
