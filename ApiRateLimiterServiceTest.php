<?php

use App\Services\ApiRateLimiter\ApiRateLimiterService;
use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    // Мокируем конфигурацию
    config(['nytimes.rate_limit.max_attempts_per_day' => 100]);
    config(['nytimes.rate_limit.max_attempts_per_minute' => 10]);

    // Создаем экземпляр сервиса
    $this->service = new ApiRateLimiterService();
});

afterEach(function () {
    RateLimiter::clear(ApiRateLimiterInterface::LIMIT_MINUTE_KEY);
});

it('hits per day rate limit', function () {
    // Ожидаем, что RateLimiter::hit будет вызван с правильными параметрами
    RateLimiter::shouldReceive('hit')
        ->once()
        ->withArgs(function ($key, $decay) {
            return str_contains($key, 'nyt:limit:daily:') && $decay > 0;
        });

    $this->service->hitPerDayRateLimit();
});

it('hits per minute rate limit', function () {
    // Ожидаем, что RateLimiter::hit будет вызван с правильными параметрами
    RateLimiter::shouldReceive('hit')
        ->once()
        ->with(ApiRateLimiterInterface::LIMIT_MINUTE_KEY, $this->service->decaySeconds());

    $this->service->hitPerMinuteRateLimit();
});

it('checks if daily attempts exceeded', function () {
    // Ожидаем, что RateLimiter::tooManyAttempts будет вызван
    RateLimiter::shouldReceive('tooManyAttempts')
        ->once()
        ->withArgs(function ($key, $maxAttempts) {
            return str_contains($key, 'nyt:limit:daily:') && $maxAttempts === 100;
        })
        ->andReturn(false);

    expect($this->service->isAttemptsPerDayExceeded())->toBeFalse();
});

it('checks if minute attempts exceeded', function () {
    // Ожидаем, что RateLimiter::tooManyAttempts будет вызван
    RateLimiter::shouldReceive('tooManyAttempts')
        ->once()
        ->with(ApiRateLimiterInterface::LIMIT_MINUTE_KEY, 10)
        ->andReturn(false);

    expect($this->service->isAttemptsPerMinuteExceeded())->toBeFalse();
});
