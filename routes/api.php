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

Route::apiResource('countries' , CountryController::class);

Route::post('/webhook', [CountryController::class, 'webhook']);
