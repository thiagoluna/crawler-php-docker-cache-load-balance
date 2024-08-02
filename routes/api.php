<?php

use App\Http\Controllers\Api\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/currencies', [CurrencyController::class, 'getCurrenciesData']);
Route::post('/v1/currencies-from-db', [CurrencyController::class, 'getCurrenciesDataFromDb']);
