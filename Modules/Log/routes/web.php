<?php

use Illuminate\Support\Facades\Route;
use Modules\Log\Http\Controllers\Admin\AuditController;
use Modules\Log\Http\Controllers\Admin\ApiLogController;

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

// ApiLog Crud Section
Route::prefix('api-logs')->name('api_logs.')->controller(ApiLogController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::get('view/{model}'                   , 'viewAsModal')->name('viewAsModal');
    Route::delete('soft-delete/{model}'         , 'softDelete')->name('softDelete');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
    Route::post('restore/{model}'               , 'restore')->name('restore');
    // Bulk Actions
    Route::delete('bulk-soft-delete'            , 'bulkSoftDelete')->name('bulkSoftDelete');
    Route::delete('bulk-hard-delete'            , 'bulkHardDelete')->name('bulkHardDelete');
    Route::post('bulk-restore'                  , 'bulkRestore')->name('bulkRestore');
});

Route::prefix('activity-log')->name('activity_log.')->controller(AuditController::class)->group(function () {
    Route::get('{type}/list/{model}'            , 'index')->name('index');
    Route::get('{type}/datatable/{model}'       , 'datatable')->name('datatable');
    Route::get('view/{model}'                   , 'viewAsModal')->name('viewAsModal');
});
