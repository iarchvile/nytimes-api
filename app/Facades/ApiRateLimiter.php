<?php

namespace App\Facades;

use App\Services\ApiRateLimiter\ApiRateLimiterService;
use Illuminate\Support\Facades\Facade;

class ApiRateLimiter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiRateLimiterService::class;
    }
}
