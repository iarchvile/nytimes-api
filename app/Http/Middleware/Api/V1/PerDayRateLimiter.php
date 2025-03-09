<?php

namespace App\Http\Middleware\Api\V1;

use App\Events\TooManyAttemptsPerDayEvent;
use App\Facades\ApiRateLimiter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerDayRateLimiter
{
    public function handle(Request $request, Closure $next): Response
    {
        if (ApiRateLimiter::isAttemptsPerDayExceeded()) {
            event(new TooManyAttemptsPerDayEvent());
            return ApiRateLimiter::tooManyAttempts();
        }

        return $next($request);
    }
}
