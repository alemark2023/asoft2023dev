<?php

 
Route::prefix('documents/not-sent')->group(function() {
    Route::get('', 'DocumentController@index')->name('tenant.documents.not_sent');
    Route::get('records', 'DocumentController@records');
    Route::get('data_table', 'DocumentController@data_table');

});
