<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Admin\Http\Controllers\Auth\AdminAuthController;
use Modules\Admin\Http\Controllers\Admin\AdminCrudController;
use Modules\Admin\Http\Controllers\Admin\DashboardController;
use Modules\Admin\Http\Controllers\Auth\AdminProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Dashboard Section
Route::prefix('home')->name('dashboard.')->controller(DashboardController::class)->middleware('active.admin')->group(function () {
    Route::match(['get', 'post'], '/', 'dashboard')->name('index');
});

// Auth Section
Route::name('auth.')->controller(AdminAuthController::class)->group(function() {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login'          , 'showLoginForm')->name('login');
        Route::post('authenticate'  , 'authenticate')->name('authenticate');
    });
    Route::get('logout' , 'logout')->name('logout')->middleware('auth:admin');
});

// Profile Section
Route::prefix('profile')->name('profile.')->controller(AdminProfileController::class)->middleware(['active.admin'])->group(function () {
    Route::get('edit'                           , 'editProfile')->name('edit');
    Route::put('update'                         , 'updateProfile')->name('update');
    Route::post('login-to-another-account'      , 'loginToAnotherAccount')->name('loginToAnotherAccount')->middleware('need.permissions:'. AdminPermissions::LOGIN_TO_ANOTHER_ACCOUNT);
    Route::post('back-to-old-account'           , 'backToOldAccount')->name('backToOldAccount');
    Route::post('update-language'               , 'updateLanguage')->name('updateLanguage');
});

// Admin Crud Section
Route::prefix('admins')->name('admins.')->controller(AdminCrudController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::get('create'                         , 'create')->name('create');
    Route::post('create'                        , 'postCreate')->name('postCreate');
    Route::get('update/{model}'                 , 'update')->name('update');
    Route::put('update/{model}'                 , 'postUpdate')->name('postUpdate');
    Route::delete('soft-delete/{model}'         , 'softDelete')->name('softDelete');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
    Route::post('restore/{model}'               , 'restore')->name('restore');
    Route::post('disable/{model}'               , 'disable')->name('disable');
    Route::post('enable/{model}'                , 'enable')->name('enable');
    // Bulk Actions
    Route::delete('bulk-soft-delete'            , 'bulkSoftDelete')->name('bulkSoftDelete');
    Route::delete('bulk-hard-delete'            , 'bulkHardDelete')->name('bulkHardDelete');
    Route::post('bulk-restore'                  , 'bulkRestore')->name('bulkRestore');
    Route::post('bulk-disable'                  , 'bulkDisable')->name('bulkDisable');
    Route::post('bulk-enable'                   , 'bulkEnable')->name('bulkEnable');
});
