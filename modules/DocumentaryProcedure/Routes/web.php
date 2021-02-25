<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
	Route::domain($hostname->fqdn)->group(function () {
		Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->prefix('documentary-procedure')->group(function () {
            Route::get('offices', 'DocumentaryOfficeController@index')->name('documentary.offices');
            Route::post('offices/store', 'DocumentaryOfficeController@store');
            Route::put('offices/{id}/update', 'DocumentaryOfficeController@update');
            Route::delete('offices/{id}/delete', 'DocumentaryOfficeController@destroy');

            Route::get('processes', 'DocumentaryProcessController@index')->name('documentary.processes');
            Route::post('processes/store', 'DocumentaryProcessController@store');
            Route::put('processes/{id}/update', 'DocumentaryProcessController@update');
            Route::delete('processes/{id}/delete', 'DocumentaryProcessController@destroy');

            Route::get('documents', 'DocumentaryDocumentController@index')->name('documentary.documents');
            Route::post('documents/store', 'DocumentaryDocumentController@store');
            Route::put('documents/{id}/update', 'DocumentaryDocumentController@update');
            Route::delete('documents/{id}/delete', 'DocumentaryDocumentController@destroy');
		});
	});
}
