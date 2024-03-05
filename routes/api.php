<?php

use App\Http\Controllers\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});

Route::apiResource('countries', CountryController::class);

Route::controller(CountryController::class)
    ->prefix('countries')
    ->name('countries.')
    ->group(function () {
        Route::post('soap', 'run_service')->middleware('auth:api');
        Route::post('webhook', 'webhook');
        Route::post('invokeSoapMethod', 'invokeSoapMethod');

    });
