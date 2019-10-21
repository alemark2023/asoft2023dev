<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth'])->group(function() {

            
            Route::get('categories', 'CategoryController@index')->name('tenant.categories.index');
            Route::get('categories/records', 'CategoryController@records');
            Route::get('categories/columns', 'CategoryController@columns'); 
            Route::get('categories/record/{category}', 'CategoryController@record');
            Route::post('categories', 'CategoryController@store');
            Route::delete('categories/{category}', 'CategoryController@destroy');
            
            Route::get('brands', 'BrandController@index')->name('tenant.brands.index');
            Route::get('brands/records', 'BrandController@records'); 
            Route::get('brands/record/{category}', 'BrandController@record');
            Route::post('brands', 'BrandController@store');
            Route::get('brands/columns', 'BrandController@columns'); 
            Route::delete('brands/{category}', 'BrandController@destroy');

        });
    });
}