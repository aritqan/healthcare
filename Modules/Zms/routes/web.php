<?php

use Illuminate\Support\Facades\Route;
use Modules\Zms\Http\Controllers\Admin\CityController;
use Modules\Zms\Http\Controllers\Admin\StateController;
use Modules\Zms\Http\Controllers\Admin\CountryController;

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

Route::prefix('countries')->name('countries.')->controller(CountryController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
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

Route::prefix('states')->name('states.')->controller(StateController::class)->group(function () {
    Route::get('{country_id?}/datatable'        , 'datatable')->name('datatable')->whereNumber('country_id');
    Route::get('update/{model}'                 , 'update')->name('update');
    Route::put('update/{model}'                 , 'postUpdate')->name('postUpdate');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
});

Route::prefix('cities')->name('cities.')->controller(CityController::class)->group(function () {
    Route::get('{state_id?}/datatable'          , 'datatable')->name('datatable')->whereNumber('state_id');
    Route::get('update/{model}'                 , 'update')->name('update');
    Route::put('update/{model}'                 , 'postUpdate')->name('postUpdate');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
});
