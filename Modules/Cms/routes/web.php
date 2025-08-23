<?php

use Modules\Cms\Http\Controllers\Admin\ContentTagController;
use Illuminate\Support\Facades\Route;
use Modules\Cms\Enums\contents\BaseContentTypes;
use Modules\Cms\Http\Controllers\Admin\ContentController;
use Modules\Cms\Http\Controllers\Admin\ContentCategoryController;

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

// ContentCategory Crud Section
Route::prefix('content/categories')->name('content_categories.')->controller(ContentCategoryController::class)->group(function () {
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

// Content Crud Section
Route::prefix('contents/{type}')->name('contents.')->controller(ContentController::class)->group(function () {
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
})->whereIn('type', BaseContentTypes::all());

// ContentTag Crud Section
Route::prefix('content_tags')->name('content_tags.')->controller(ContentTagController::class)->group(function () {
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
