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

Route::prefix('restaurant')->group(function() {
    // para configuracion de productos a mostrar
    Route::get('/list/items', 'RestaurantController@list_items')->name('tenant.restaurant.list_items');
    Route::post('items/visible', 'RestaurantController@is_visible');
});

// ruta publica
Route::middleware(['locked.tenant'])->group(function() {
    // restaurant
    Route::get('/menu', 'RestaurantController@menu')->name('tenant.restaurant.menu');
    Route::get('/menu/category/{name}', 'RestaurantController@category')->name('tenant.restaurant.category');
});
