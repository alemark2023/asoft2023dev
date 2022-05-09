<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {

            Route::prefix('payment-configurations')->group(function () {

                Route::post('', 'PaymentConfigurationController@store');
                Route::get('/record', 'PaymentConfigurationController@record');
                Route::post('upload-qrcode-yape', 'PaymentConfigurationController@uploadQrcodeYape');

            });

            Route::prefix('payment-links')->group(function () {

                Route::post('', 'PaymentLinkController@store');
                Route::get('record/{document_payment_id}/{instance_type}/{payment_link_type_id}', 'PaymentLinkController@record');

            });

        });
    });
}
