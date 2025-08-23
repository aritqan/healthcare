<?php

use Illuminate\Support\Facades\Route;
use Modules\Zms\Http\Controllers\Api\CountryController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::controller(CountryController::class)->prefix('countries')->group(function () {
    Route::get('list'           , 'list');
    Route::get('show/{id}'      , 'show');
});
