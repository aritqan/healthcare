<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\Http\Controllers\RoleController;
use Modules\Permission\Http\Controllers\PermissionController;

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

// Roles Section
Route::prefix('roles')->name('roles.')->controller(RoleController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('create'                         , 'create')->name('create');
    Route::post('create'                        , 'postCreate')->name('postCreate');
    Route::get('update/{model}'                 , 'update')->name('update');
    Route::put('update/{model}'                 , 'postUpdate')->name('postUpdate');
    Route::delete('soft-delete/{model}'         , 'softDelete')->name('softDelete');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
    Route::post('restore/{model}'               , 'restore')->name('restore');
});

// Permissions Section
Route::prefix('permissions')->name('permissions.')->controller(PermissionController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('create'                         , 'create')->name('create');
    Route::post('create'                        , 'postCreate')->name('postCreate');
    Route::get('update/{model}'                 , 'update')->name('update');
    Route::put('update/{model}'                 , 'postUpdate')->name('postUpdate');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
});
