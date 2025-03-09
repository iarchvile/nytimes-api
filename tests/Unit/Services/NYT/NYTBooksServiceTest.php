<?php

namespace Tests\Unit\Services\NYT;

use App\Services\ApiRateLimiter\Contracts\ApiRateLimiterInterface;
use App\Services\NYT\Api\BooksRepositoryInterface;
use App\Services\NYT\Api\BooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class NYTBooksServiceTest extends TestCase
{
    private BooksRepositoryInterface $repository;
    private ApiRateLimiterInterface $rateLimiter;
    private BooksService $service;

    public function test_it_fetches_best_sellers_history_and_caches_response()
    {
        $queryParams = ['author' => 'Stephen King'];
        $requestCacheKey = 'cache_key';
        $responseData = ['results' => []];

        $this->repository
            ->shouldReceive('getBestSellersHistory')
            ->with($queryParams)
            ->once()
            ->andReturn($responseData);

        $this->rateLimiter
            ->shouldReceive('hitPerDayRateLimit')
            ->once();
        $this->rateLimiter
            ->shouldReceive('hitPerMinuteRateLimit')
            ->once();
        $this->rateLimiter
            ->shouldReceive('decaySeconds')
            ->once()
            ->andReturn(60);

        $result = $this->service->getBestSellersHistory($requestCacheKey, $queryParams);

        $this->assertEquals($responseData, $result);
        $this->assertEquals($responseData, Cache::get($requestCacheKey));
    }

    public function test_it_generates_request_key()
    {
        $request = new Request(['author' => 'Stephen King']);
        $key = $this->service->getRequestKey($request);
        $this->assertStringStartsWith(ApiRateLimiterInterface::LIMIT_MINUTE_KEY, $key);
        $this->assertStringEndsWith(md5(serialize($request->all())), $key);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(BooksRepositoryInterface::class);
        $this->rateLimiter = Mockery::mock(ApiRateLimiterInterface::class);

        $this->service = new BooksService($this->repository, $this->rateLimiter);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        Cache::flush();
        parent::tearDown();
    }

}
