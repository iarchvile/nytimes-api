<?php

namespace App\Services\NYT\Api;

interface BooksRepositoryInterface
{
    public function getBestSellersHistory(array $queryParams): array;
}
