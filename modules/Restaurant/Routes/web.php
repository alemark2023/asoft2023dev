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
    Route::get('cart', 'RestaurantController@detailCart')->name('restaurant.detail.cart');
    Route::post('payment_cash', 'RestaurantController@paymentCash')->name('restaurant.payment.cash');



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

    //Orders
    Route::prefix('orders')->group(function() {

        Route::get('', 'OrderController@index')->name('tenant.restaurant.order.index');
        Route::get('columns', 'OrderController@columns');
        Route::get('records', 'OrderController@records');
        Route::get('record/{order}', 'OrderController@record');
        Route::get('pdf/{id}', 'OrderController@pdf');

        //warehouse
        Route::post('warehouse', 'OrderController@searchWarehouse');
        Route::get('tables', 'OrderController@tables');
        Route::get('tables/item/{internal_id}', 'OrderController@item');

    });



    
            

});

// ruta publica
Route::middleware(['locked.tenant'])->group(function() {
    // restaurant
    Route::get('/menu/{name?}', 'RestaurantController@menu')->name('tenant.restaurant.menu');


});
