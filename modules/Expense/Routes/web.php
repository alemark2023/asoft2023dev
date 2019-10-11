<?php

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module'])->group(function () {

            // Route::redirect('/', '/dashboard');

            Route::prefix('expenses')->group(function () { 

                Route::get('', 'ExpenseController@index')->name('tenant.expenses.index');
                Route::get('columns', 'ExpenseController@columns');
                Route::get('records', 'ExpenseController@records');
                Route::get('records/expense-payments/{expense}', 'ExpenseController@recordsExpensePayments');
                Route::get('create', 'ExpenseController@create')->name('tenant.expenses.create');
                Route::get('tables', 'ExpenseController@tables');
                Route::get('table/{table}', 'ExpenseController@table');
                Route::post('', 'ExpenseController@store');
                Route::get('record/{expense}', 'ExpenseController@record'); 

            });

        });
    });
}