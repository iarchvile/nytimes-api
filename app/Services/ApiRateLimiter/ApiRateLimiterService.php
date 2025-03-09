<?php

namespace App\Services\ApiRateLimiter;

use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimiterService implements ApiRateLimiterInterface
{
    private string $dailyKey;
    private int $maxAttemptsPerDay;
    private int $maxAttemptsPerMinute;
    private int $decaySeconds;
    private Carbon $currentNewYorkTime;

    public function __construct()
    {
        $this->maxAttemptsPerDay = config('nytimes.rate_limit.max_attempts_per_day');
        $this->maxAttemptsPerMinute = config('nytimes.rate_limit.max_attempts_per_minute');
        $this->currentNewYorkTime = Carbon::now('America/New_York');
        $this->dailyKey = 'nyt:limit:daily:' . $this->currentNewYorkTime->toDateString();

        if (empty($this->maxAttemptsPerMinute) || $this->maxAttemptsPerMinute < 0) {
            throw new RuntimeException('maxAttemptsPerMinute is not set.');
        }

        $this->decaySeconds = 60 / $this->maxAttemptsPerMinute;
    }

    public function decaySeconds(): int
    {
        return $this->decaySeconds;
    }

    public function hitPerDayRateLimit(): void
    {
        RateLimiter::hit($this->dailyKey, $this->currentNewYorkTime->diffInSeconds(
            $this->currentNewYorkTime->copy()->endOfDay()
        ));
    }

    public function hitPerMinuteRateLimit(): void
    {
        RateLimiter::hit(self::LIMIT_MINUTE_KEY, $this->decaySeconds);
    }

    public function isAttemptsPerDayExceeded(): bool
    {
        return RateLimiter::tooManyAttempts($this->dailyKey, $this->maxAttemptsPerDay);
    }

    public function tooManyAttempts(): void
    {
        abort(Response::HTTP_TOO_MANY_REQUESTS, Response::$statusTexts[Response::HTTP_TOO_MANY_REQUESTS]);
    }

    public function isAttemptsPerMinuteExceeded(): bool
    {
        return RateLimiter::tooManyAttempts(self::LIMIT_MINUTE_KEY, $this->maxAttemptsPerMinute);
    }
}
