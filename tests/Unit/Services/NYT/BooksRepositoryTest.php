<?php

namespace Tests\Unit\Services\NYT;

use App\Services\NYT\Api\BooksRepository;
use Exception;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BooksRepositoryTest extends TestCase
{
    public function test_it_fetches_best_sellers_history_from_nyt_api()
    {
        Http::fake([
            '*/lists/best-sellers/history.json*' => Http::response(['results' => []], 200),
        ]);

        $repository = new BooksRepository();
        $response = $repository->getBestSellersHistory(['author' => 'Stephen King']);

        $this->assertArrayHasKey('results', $response);

        Http::assertSent(function (Request $request) {
            $expectedBaseUrl = config('nytimes.base_url') . '/' . config('nytimes.api.books.endpoint') . '/lists/best-sellers/history.json';
            $this->assertStringStartsWith($expectedBaseUrl, $request->url());

            parse_str(parse_url($request->url(), PHP_URL_QUERY), $queryParams);
            $this->assertEquals(config('nytimes.api.books.key'), $queryParams['api-key']);
            $this->assertEquals('Stephen King', $queryParams['author']);

            return true;
        });
    }

    public function test_it_throws_exception_when_nyt_api_request_fails()
    {
        Http::fake([
            '*/lists/best-sellers/history.json*' => Http::response([], 500),
        ]);

        $repository = new BooksRepository();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to fetch best sellers history from NYT API.');

        $repository->getBestSellersHistory(['author' => 'Stephen King']);
    }
}
