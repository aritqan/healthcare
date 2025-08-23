<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Cms\Http\Controllers\Api\ContentController;

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

Route::controller(ContentController::class)->prefix('content/{type}')->group(function () {
    Route::get('list'           , 'list');
    Route::get('show/{slug}'    , 'show');
});
