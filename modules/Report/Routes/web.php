<?php

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function () {


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
                Route::get('sale-notes/records', 'ReportSaleNoteController@records')->name('tenant.reports.sale_notes.records');

                Route::get('quotations', 'ReportQuotationController@index')->name('tenant.reports.quotations.index');
                Route::get('quotations/pdf', 'ReportQuotationController@pdf')->name('tenant.reports.quotations.pdf');
                Route::get('quotations/excel', 'ReportQuotationController@excel')->name('tenant.reports.quotations.excel');
                Route::get('quotations/filter', 'ReportQuotationController@filter')->name('tenant.reports.quotations.filter');
                Route::get('quotations/records', 'ReportQuotationController@records')->name('tenant.reports.quotations.records');
                
                Route::get('cash', 'ReportCashController@index')->name('tenant.reports.cash.index');
                Route::get('cash/pdf', 'ReportCashController@pdf')->name('tenant.reports.cash.pdf');
                Route::get('cash/excel', 'ReportCashController@excel')->name('tenant.reports.cash.excel');
                Route::get('cash/filter', 'ReportCashController@filter')->name('tenant.reports.cash.filter');
                Route::get('cash/records', 'ReportCashController@records')->name('tenant.reports.cash.records');


                
                Route::get('document-hotels', 'ReportDocumentHotelController@index')->name('tenant.reports.document_hotels.index');
                Route::get('document-hotels/pdf', 'ReportDocumentHotelController@pdf')->name('tenant.reports.document_hotels.pdf');
                Route::get('document-hotels/excel', 'ReportDocumentHotelController@excel')->name('tenant.reports.document_hotels.excel');
                Route::get('document-hotels/filter', 'ReportDocumentHotelController@filter')->name('tenant.reports.document_hotels.filter');
                Route::get('document-hotels/records', 'ReportDocumentHotelController@records')->name('tenant.reports.document_hotels.records');

            });

        });
    });
}