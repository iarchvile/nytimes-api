<?php

namespace Tests\Unit\Services;

use App\Services\ApiRateLimiter\ApiRateLimiterService;
use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ApiRateLimiterServiceTest extends TestCase
{
    private ApiRateLimiterService $service;

    public function test_it_hits_per_day_rate_limit()
    {
        RateLimiter::shouldReceive('hit')
            ->once()
            ->withArgs(function ($key, $decay) {
                return str_contains($key, 'nyt:limit:daily:') && $decay > 0;
            });

        $this->service->hitPerDayRateLimit();
    }

    public function test_it_hits_per_minute_rate_limit()
    {
        RateLimiter::shouldReceive('hit')
            ->once()
            ->with(ApiRateLimiterInterface::LIMIT_MINUTE_KEY, $this->service->decaySeconds());

        $this->service->hitPerMinuteRateLimit();
    }

    public function test_it_checks_if_daily_attempts_exceeded()
    {
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->withArgs(function ($key, $maxAttempts) {
                return str_contains($key, 'nyt:limit:daily:') && $maxAttempts === 100;
            })
            ->andReturn(false);

        $this->assertFalse($this->service->isAttemptsPerDayExceeded());
    }

    public function test_it_checks_if_minute_attempts_exceeded()
    {
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with(ApiRateLimiterInterface::LIMIT_MINUTE_KEY, 10)
            ->andReturn(false);

        $this->assertFalse($this->service->isAttemptsPerMinuteExceeded());
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['nytimes.rate_limit.max_attempts_per_day' => 100]);
        config(['nytimes.rate_limit.max_attempts_per_minute' => 10]);

        $this->service = new ApiRateLimiterService();
    }

    protected function tearDown(): void
    {
        RateLimiter::clearResolvedInstances();

        parent::tearDown();
    }
}
