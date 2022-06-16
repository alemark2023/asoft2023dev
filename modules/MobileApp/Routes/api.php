<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) 
{
    Route::domain($hostname->fqdn)->group(function () {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function () {

            Route::prefix('app-configurations')->group(function () {

                Route::get('record', 'Api\AppConfigurationController@record');
                Route::post('', 'Api\AppConfigurationController@store');

            });

            
            Route::prefix('items')->group(function () {
                
                Route::post('update', 'Api\ItemController@update');
                Route::get('records', 'Api\ItemController@records');
                Route::get('record/{id}', 'Api\ItemController@record');
                Route::post('upload-temp-image', 'Api\ItemController@uploadTempImage');
                Route::delete('{id}', 'Api\ItemController@destroy');
                Route::get('change-active/{id}/{active}', 'Api\ItemController@changeActive');

            });

        }); 
    });
} 
