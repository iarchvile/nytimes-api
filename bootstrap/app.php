<?php

use App\Http\Middleware\Api\V1\PerDayRateLimiter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api/v1/api.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->api([
            PerDayRateLimiter::class
        ]);

        //$middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
