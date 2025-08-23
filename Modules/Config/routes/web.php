<?php

use Illuminate\Support\Facades\Route;
use Modules\Config\Http\Controllers\Admin\SettingController;

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

Route::prefix('settings')->name('settings.')->controller(SettingController::class)->group(function () {
    Route::get('list'       , 'index')->name('index');
    Route::get('create'     , 'create')->name('create');
    Route::post('create'    , 'postCreate')->name('postCreate');
    Route::put('update'     , 'postUpdate')->name('postUpdate');
});
