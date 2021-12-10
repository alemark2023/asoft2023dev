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

    use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($current_hostname) {
        Route::domain($current_hostname->fqdn)->group(function () {
            Route::middleware(['auth', 'locked.tenant'])->group(function () {

                /**
                 * item-production
                 * item-production/columns,
                 * item-production/records,
                 * item-production/tables,
                 * item-production/record/{item},
                 * item-production/{item},
                 * item-production/item-unit-type/{item},
                 * item-production/import,
                 * item-production/upload,
                 * item-production/visible_store,
                 * item-production/item/tables,
                 */
                Route::prefix('item-production')->group(function () {

                    // @todo Pasar al modulo de produccion
                    Route::get('', 'Tenant\ItemSetController@index')->name('tenant.item_production.index'); // ->middleware('redirect.level');
                    Route::post('', 'Tenant\ItemSetController@store');
                    Route::get('/columns', 'Tenant\ItemSetController@columns');
                    Route::get('/records', 'Tenant\ItemSetController@records');
                    Route::get('/tables', 'Tenant\ItemSetController@tables');
                    Route::get('/record/{item}', 'Tenant\ItemSetController@record');
                    Route::delete('/{item}', 'Tenant\ItemSetController@destroy');
                    Route::delete('/item-unit-type/{item}', 'Tenant\ItemSetController@destroyItemUnitType');
                    Route::post('/import', 'Tenant\ItemSetController@import');
                    Route::post('/upload', 'Tenant\ItemSetController@upload');
                    Route::post('/visible_store', 'Tenant\ItemSetController@visibleStore');
                    Route::get('/item/tables', 'Tenant\ItemSetController@item_tables');
                });

                Route::prefix('mill-production')->group(function () {

                    // @todo Pasar al modulo de produccion
                    Route::get('', 'MillController@index')->name('tenant.mill_production.index'); // ->middleware('redirect.level');
                    Route::get('/create', 'MillController@create');
                    Route::post('/create', 'MillController@create');
                    Route::post('', 'MillController@store');
                    Route::get('/columns', 'MillController@columns');
                    Route::get('/records', 'MillController@records');
                    Route::get('/tables', 'MillController@tables');
                    Route::get('/record/{item}', 'MillController@record');
                    Route::delete('/{item}', 'MillController@destroy');
                    Route::delete('/item-unit-type/{item}', 'MillController@destroyItemUnitType');
                    Route::post('/import', 'MillController@import');
                    Route::post('/upload', 'MillController@upload');
                    Route::post('/visible_store', 'MillController@visibleStore');
                    Route::get('/item/tables', 'MillController@item_tables');
                });
            });
        });
    }


