<?php

use App\Services\CrawlingWikipediaService;
use Illuminate\Support\Facades\Route;

Route::post('/v1/currencies', [\App\Docs\Api\V1\CurrencyController::class, 'getCurrenciesData']);
Route::post('/v1/currencies-from-db', [\App\Docs\Api\V1\CurrencyController::class, 'getCurrenciesDataFromDb']);

Route::get('/v1/crawler', [\App\Http\Controllers\Api\CrawlerController::class, 'getCrawler']);
