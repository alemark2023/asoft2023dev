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
    Route::get('item_partial/{id}', 'RestaurantController@partialItem')->name('restaurant.item_partial');
    Route::get('item/{id}/{promotion_id?}', 'RestaurantController@item')->name('restaurant.item');


    //Promotion
    Route::prefix('promotions')->group(function() {

        Route::get('', 'PromotionController@index')->name('tenant.restaurant.promotion.index');
        Route::get('columns', 'PromotionController@columns');
        Route::get('tables', 'PromotionController@tables');
        Route::get('records', 'PromotionController@records');
        Route::get('record/{tag}', 'PromotionController@record');
        Route::post('', 'PromotionController@store');
        Route::delete('{promotion}', 'PromotionController@destroy');
        Route::post('upload', 'PromotionController@upload');

    });

    

});

// ruta publica
Route::middleware(['locked.tenant'])->group(function() {
    // restaurant
    Route::get('/menu/{name?}', 'RestaurantController@menu')->name('tenant.restaurant.menu');

});
