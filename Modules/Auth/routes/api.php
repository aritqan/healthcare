<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\AddressController;
use Modules\Auth\Http\Controllers\Api\AuthController;
use Modules\Auth\Http\Controllers\Api\ProfileController;
use Modules\Auth\Http\Controllers\Api\AuthSocialController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('register'          , 'register');
    Route::post('login'             , 'login');
    Route::post('forget-password'   , 'forgetPassword');
    Route::post('logout'            , 'logout')->middleware('auth:sanctum');
});

Route::controller(AuthSocialController::class)->prefix('auth/{provider}')->group(function() {
    Route::get('redirect'   , 'redirectToProvider');
    Route::get('callback'   , 'handlProviderCallback');
});

Route::controller(ProfileController::class)->middleware(['auth:sanctum', 'active.user'])->group(function () {
    Route::get('profile'            , 'profile');
    Route::post('change-language'   , 'changeLanguage');
    Route::post('chage-password'    , 'changePassword');
    Route::post('update-profile'    , 'updateProfile');
});

Route::controller(AddressController::class)->middleware(['auth:sanctum', 'active.user'])->prefix('address')->group(function () {
    Route::get('list'           , 'list');
    Route::post('create'        , 'store');
    Route::get('show/{id}'      , 'show');
    Route::put('update/{id}'    , 'update');
    Route::delete('delete/{id}' , 'destroy');
});
