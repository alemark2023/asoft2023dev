<?php

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module'])->group(function () {


            Route::prefix('reports')->group(function () { 

                
                Route::get('purchases', 'ReportPurchaseController@index')->name('tenant.reports.purchases.index');
                Route::get('purchases/pdf', 'ReportPurchaseController@pdf')->name('tenant.reports.purchases.pdf');
                Route::get('purchases/excel', 'ReportPurchaseController@excel')->name('tenant.reports.purchases.excel');
                Route::get('purchases/filter', 'ReportPurchaseController@filter')->name('tenant.reports.purchases.filter');
                Route::get('purchases/records', 'ReportPurchaseController@records')->name('tenant.reports.purchases.records');
 
                Route::get('sales', 'ReportDocumentController@index')->name('tenant.reports.sales.index');
                Route::get('sales/pdf', 'ReportDocumentController@pdf')->name('tenant.reports.sales.pdf');
                Route::get('sales/excel', 'ReportDocumentController@excel')->name('tenant.reports.sales.excel');
                Route::get('sales/filter', 'ReportDocumentController@filter')->name('tenant.reports.sales.filter');
                Route::get('sales/records', 'ReportDocumentController@records')->name('tenant.reports.sales.records');

                Route::get('sale-notes', 'ReportSaleNoteController@index')->name('tenant.reports.sale_notes.index');
                Route::get('sale-notes/pdf', 'ReportSaleNoteController@pdf')->name('tenant.reports.sale_notes.pdf');
                Route::get('sale-notes/excel', 'ReportSaleNoteController@excel')->name('tenant.reports.sale_notes.excel');
                Route::get('sale-notes/filter', 'ReportSaleNoteController@filter')->name('tenant.reports.sale_notes.filter');
                Route::get('sale-notes/records', 'ReportSaleNoteController@records')->name('tenant.reports.sales.records');

            });

        });
    });
}