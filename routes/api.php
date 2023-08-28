<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::name('weather.')
    ->group(function () {
        Route::get('/weather-sources', [WeatherController::class, 'getSourceList'])
            ->name('sources');

        Route::get('/weather/{source}', [WeatherController::class, 'getSourceData'])
            ->name('source_data');

        Route::get('/weather-aggregate', [WeatherController::class, 'getAggregateData'])
            ->name('aggregate_data');
    });
