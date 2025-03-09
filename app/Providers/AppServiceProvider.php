<?php

namespace App\Providers;

use App\Services\ApiRateLimiter\ApiRateLimiterService;
use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use App\Services\NYT\Api\BooksRepository;
use App\Services\NYT\Api\BooksRepositoryInterface;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $this->app->singleton('ApiRateLimiter', fn() => new ApiRateLimiterService());
        $this->app->bind(ApiRateLimiterInterface::class, ApiRateLimiterService::class);
        $this->app->bind(BooksRepositoryInterface::class, BooksRepository::class);
    }

}
