<?php

use App\Http\Api\V1\Controllers\BestSellersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('best-sellers/history', [BestSellersController::class, 'history']);
});
