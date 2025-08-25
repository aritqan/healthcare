<?php

use Modules\Nabd\Http\Controllers\Admin\DoktorController;
use Modules\Nabd\Http\Controllers\Admin\PharmacyController;
use Modules\Nabd\Http\Controllers\Admin\ClinicController;
use Illuminate\Support\Facades\Route;
use Modules\Nabd\Http\Controllers\Admin\NabdController;

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

Route::group([], function () {
    Route::resource('nabd', NabdController::class)->names('nabd');
});

// Clinic Crud Section
Route::prefix('clinics')->name('clinics.')->controller(ClinicController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::get('create'                         , 'create')->name('create');
    Route::get('view/{model}'                   , 'view')->name('view');
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

// Pharmacy Crud Section
Route::prefix('pharmacies')->name('pharmacies.')->controller(PharmacyController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::get('create'                         , 'create')->name('create');
    Route::get('view/{model}'                   , 'view')->name('view');
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

// Doktor Crud Section
Route::prefix('doktors')->name('doktors.')->controller(DoktorController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::get('create'                         , 'create')->name('create');
    Route::get('view/{model}'                   , 'view')->name('view');
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
