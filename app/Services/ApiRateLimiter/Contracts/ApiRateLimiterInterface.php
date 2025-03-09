<?php

namespace App\Services\ApiRateLimiter\Contracts;

use Illuminate\Http\Request;

interface ApiRateLimiterInterface
{
    public function decaySeconds(): int;

    public function getRequestKey(Request $request): string;

    public function cacheResponse(string $requestCacheKey, array $response): void;

    public function hitPerDayRateLimit(): void;

    public function hitPerMinuteRateLimit(): void;

    public function isAttemptsPerDayExceeded(): bool;

    public function tooManyAttempts(): void;

    public function isAttemptsPerMinuteExceeded(): bool;
}
