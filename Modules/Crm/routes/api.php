<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Controllers\Api\ContactusController;
use Modules\Crm\Http\Controllers\Api\SubscribeController;

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

Route::post('contact-us', [ContactusController::class, 'store']);

Route::post('subscribe', [SubscribeController::class, 'store']);
