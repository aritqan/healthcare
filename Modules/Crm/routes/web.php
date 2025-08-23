<?php

use Modules\Crm\Http\Controllers\Admin\SubscribeController;
use Modules\Crm\Http\Controllers\Admin\ContactusController;
use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Controllers\Admin\CrmController;

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

// Contactus Crud Section
Route::prefix('contactuses')->name('contactuses.')->controller(ContactusController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::get('view/{model}'                   , 'view')->name('view');
    Route::post('send-reply/{model}'            , 'sendReply')->name('sendReply');
    Route::delete('soft-delete/{model}'         , 'softDelete')->name('softDelete');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
    Route::post('restore/{model}'               , 'restore')->name('restore');
    // Bulk Actions
    Route::delete('bulk-soft-delete'            , 'bulkSoftDelete')->name('bulkSoftDelete');
    Route::delete('bulk-hard-delete'            , 'bulkHardDelete')->name('bulkHardDelete');
    Route::post('bulk-restore'                  , 'bulkRestore')->name('bulkRestore');
});

// Subscribe Crud Section
Route::prefix('subscribes')->name('subscribes.')->controller(SubscribeController::class)->group(function () {
    Route::get('list'                           , 'index')->name('index');
    Route::get('datatable'                      , 'datatable')->name('datatable');
    Route::get('ajax-list'                      , 'ajaxList')->name('ajaxList');
    Route::delete('soft-delete/{model}'         , 'softDelete')->name('softDelete');
    Route::delete('hard-delete/{model}'         , 'hardDelete')->name('hardDelete');
    Route::post('restore/{model}'               , 'restore')->name('restore');
    // Bulk Actions
    Route::delete('bulk-soft-delete'            , 'bulkSoftDelete')->name('bulkSoftDelete');
    Route::delete('bulk-hard-delete'            , 'bulkHardDelete')->name('bulkHardDelete');
    Route::post('bulk-restore'                  , 'bulkRestore')->name('bulkRestore');
});
