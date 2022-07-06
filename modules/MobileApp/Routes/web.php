<?php

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

// Route::prefix('mobileapp')->group(function() {
//     Route::get('/', 'MobileAppController@index');
// });

Route::prefix('live-app')->group(function() {
    Route::get('/', 'LiveAppController@index')->name('tenant.liveapp.index');

    Route::middleware(['auth'])->group(function () {
        Route::get('/configuration', 'LiveAppController@configuration')->name('tenant.liveapp.configuration');
    });
});
