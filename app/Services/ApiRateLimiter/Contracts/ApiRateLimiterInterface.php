<?php

namespace App\Services\ApiRateLimiter\Contracts;

interface ApiRateLimiterInterface
{
    const LIMIT_MINUTE_KEY = 'nyt:limit:minute';

    public function decaySeconds(): int;

    public function hitPerDayRateLimit(): void;

    public function hitPerMinuteRateLimit(): void;

    public function isAttemptsPerDayExceeded(): bool;

    public function tooManyAttempts(): void;

    public function isAttemptsPerMinuteExceeded(): bool;
}
