<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\Api\FirebaseTokenController;

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

Route::controller(FirebaseTokenController::class)->middleware(['auth:sanctum', 'active.user'])->group(function () {
    Route::post('save-fcm-token', 'saveFcmToken');
});
