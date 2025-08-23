<?php

use Illuminate\Support\Facades\Route;
use Modules\Base\Http\Controllers\ActionController;
use Modules\Base\Http\Controllers\Admin\TestController;
use Modules\Config\Enums\permissions\SettingPermissions;

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

Route::get('empty-data', function() {
    return response()->json([]);
})->name('empty_data');

Route::controller(ActionController::class)->middleware(['active.admin', 'need.permissions:' . SettingPermissions::EXECUTE_ACTION])->group(function() {
    Route::post('clear-cache'       , 'clearCache')->name('clearCache');
    Route::post('clear-logs'        , 'clearLogs')->name('clearLogs');
    Route::post('reset-permissions' , 'resetPermissions')->name('resetPermissions');
});

// Test Section
Route::prefix('test')->name('test.')->controller(TestController::class)->middleware(['active.admin', 'only.dev.env'])->group(function () {
    Route::get('/'              , 'index')->name('index');
    Route::get('preview-email'  , 'previewEmail')->name('previewEmail');
});
