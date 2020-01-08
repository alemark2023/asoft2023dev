<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {

        Auth::routes();

        Route::get('search', 'Tenant\SearchController@index')->name('search.index');
        Route::get('buscar', 'Tenant\SearchController@index')->name('search.index');
        Route::get('search/tables', 'Tenant\SearchController@tables');
        Route::post('search', 'Tenant\SearchController@store');

        //Ecommerce
        /*Route::get('ecommerce', 'Tenant\EcommerceController@index')->name('tenant.ecommerce.index');
        Route::get('ecommerce/item/{id}', 'Tenant\EcommerceController@item')->name('tenant.ecommerce.item');
        Route::get('ecommerce/items', 'Tenant\EcommerceController@items')->name('tenant.ecommerce.item.index');
        Route::get('ecommerce/item_partial/{id}', 'Tenant\EcommerceController@partialItem')->name('item_partial');
        Route::get('ecommerce/detail_cart', 'Tenant\EcommerceController@detailCart')->name('tenant_detail_cart');
        Route::get('ecommerce/pay_cart', 'Tenant\EcommerceController@pay')->name('tenant_pay_cart');
        Route::get('ecommerce/login', 'Tenant\EcommerceController@showLogin')->name('tenant_ecommerce_login');
        Route::get('ecommerce/logout', 'Tenant\EcommerceController@logout')->name('tenant_ecommerce_logout');
        Route::get('ecommerce/items_bar', 'Tenant\EcommerceController@itemsBar');
        Route::post('ecommerce/login', 'Tenant\EcommerceController@login')->name('tenant_ecommerce_login');
        Route::post('ecommerce/storeUser', 'Tenant\EcommerceController@storeUser')->name('tenant_ecommerce_store_user');
        Route::post('ecommerce/rating_item', 'Tenant\EcommerceController@ratingItem')->name('tenant_ecommerce_rating_item');
        Route::get('ecommerce/rating_item/{id}', 'Tenant\EcommerceController@getRating');*/



        Route::get('downloads/{model}/{type}/{external_id}/{format?}', 'Tenant\DownloadController@downloadExternal')->name('tenant.download.external_id');
        Route::get('print/{model}/{external_id}/{format?}', 'Tenant\DownloadController@toPrint');

       Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function() {
        // Route::middleware(['auth'])->group(function() {
            // Route::get('/', function () {
            //     return redirect()->route('tenant.documents.create');
            // });
            // Route::get('dashboard', 'Tenant\HomeController@index')->name('tenant.dashboard');

            Route::get('catalogs', 'Tenant\CatalogController@index')->name('tenant.catalogs.index');
            Route::get('advanced', 'Tenant\AdvancedController@index')->name('tenant.advanced.index');

            Route::get('tasks', 'Tenant\TaskController@index')->name('tenant.tasks.index');
            Route::post('tasks/commands', 'Tenant\TaskController@listsCommand');
            Route::post('tasks/tables', 'Tenant\TaskController@tables');
            Route::post('tasks', 'Tenant\TaskController@store');
            Route::delete('tasks/{task}', 'Tenant\TaskController@destroy');

            //Ecommerce
            /*Route::post('ecommerce/culqi', 'Tenant\CulqiController@payment')->name('tenant_ecommerce_culqui');
            Route::post('ecommerce/transaction_finally', 'Tenant\EcommerceController@transactionFinally')->name('tenant_ecommerce_transaction_finally');
            Route::post('ecommerce/payment_cash', 'Tenant\EcommerceController@paymentCash')->name('tenant_ecommerce_payment_cash');*/


            //Orders
            Route::get('orders', 'Tenant\OrderController@index')->name('tenant_orders_index');
            Route::get('orders/columns', 'Tenant\OrderController@columns');
            Route::get('orders/records', 'Tenant\OrderController@records');



            //Company
            Route::get('companies/create', 'Tenant\CompanyController@create')->name('tenant.companies.create');
            Route::get('companies/tables', 'Tenant\CompanyController@tables');
            Route::get('companies/record', 'Tenant\CompanyController@record');
            Route::post('companies', 'Tenant\CompanyController@store');
            Route::post('companies/uploads', 'Tenant\CompanyController@uploadFile');


            //Card Brands
            Route::get('card_brands/records', 'Tenant\CardBrandController@records');
            Route::get('card_brands/record/{card_brand}', 'Tenant\CardBrandController@record');
            Route::post('card_brands', 'Tenant\CardBrandController@store');
            Route::delete('card_brands/{card_brand}', 'Tenant\CardBrandController@destroy');

            //Configurations
            Route::get('configurations/create', 'Tenant\ConfigurationController@create')->name('tenant.configurations.create');
            Route::get('configurations/record', 'Tenant\ConfigurationController@record');
            Route::post('configurations', 'Tenant\ConfigurationController@store');

            //Certificates
            Route::get('certificates/record', 'Tenant\CertificateController@record');
            Route::post('certificates/uploads', 'Tenant\CertificateController@uploadFile');
            Route::delete('certificates', 'Tenant\CertificateController@destroy');

            //Establishments
            Route::get('establishments', 'Tenant\EstablishmentController@index')->name('tenant.establishments.index');
            Route::get('establishments/create', 'Tenant\EstablishmentController@create');
            Route::get('establishments/tables', 'Tenant\EstablishmentController@tables');
            Route::get('establishments/record/{establishment}', 'Tenant\EstablishmentController@record');
            Route::post('establishments', 'Tenant\EstablishmentController@store');
            Route::get('establishments/records', 'Tenant\EstablishmentController@records');
            Route::delete('establishments/{establishment}', 'Tenant\EstablishmentController@destroy');

            //Bank Accounts
            Route::get('bank_accounts', 'Tenant\BankAccountController@index')->name('tenant.bank_accounts.index');
            Route::get('bank_accounts/records', 'Tenant\BankAccountController@records');
            Route::get('bank_accounts/create', 'Tenant\BankAccountController@create');
            Route::get('bank_accounts/tables', 'Tenant\BankAccountController@tables');
            Route::get('bank_accounts/record/{bank_account}', 'Tenant\BankAccountController@record');
            Route::post('bank_accounts', 'Tenant\BankAccountController@store');
            Route::delete('bank_accounts/{bank_account}', 'Tenant\BankAccountController@destroy');

            //Series
            Route::get('series/records/{establishment}', 'Tenant\SeriesController@records');
            Route::get('series/create', 'Tenant\SeriesController@create');
            Route::get('series/tables', 'Tenant\SeriesController@tables');
            Route::post('series', 'Tenant\SeriesController@store');
            Route::delete('series/{series}', 'Tenant\SeriesController@destroy');

            //Users
            Route::get('users', 'Tenant\UserController@index')->name('tenant.users.index');
            Route::get('users/create', 'Tenant\UserController@create')->name('tenant.users.create');
            Route::get('users/tables', 'Tenant\UserController@tables');
            Route::get('users/record/{user}', 'Tenant\UserController@record');
            Route::post('users', 'Tenant\UserController@store');
            Route::get('users/records', 'Tenant\UserController@records');
            Route::delete('users/{user}', 'Tenant\UserController@destroy');

            //ChargeDiscounts
            Route::get('charge_discounts', 'Tenant\ChargeDiscountController@index')->name('tenant.charge_discounts.index');
            Route::get('charge_discounts/records/{type}', 'Tenant\ChargeDiscountController@records');
            Route::get('charge_discounts/create', 'Tenant\ChargeDiscountController@create');
            Route::get('charge_discounts/tables/{type}', 'Tenant\ChargeDiscountController@tables');
            Route::get('charge_discounts/record/{charge}', 'Tenant\ChargeDiscountController@record');
            Route::post('charge_discounts', 'Tenant\ChargeDiscountController@store');
            Route::delete('charge_discounts/{charge}', 'Tenant\ChargeDiscountController@destroy');


            //Items Ecommerce
            Route::get('items_ecommerce', 'Tenant\ItemController@index_ecommerce')->name('tenant.items_ecommerce.index');

            //Items
            Route::get('items', 'Tenant\ItemController@index')->name('tenant.items.index');
            Route::get('items/columns', 'Tenant\ItemController@columns');
            Route::get('items/records', 'Tenant\ItemController@records');
            Route::get('items/tables', 'Tenant\ItemController@tables');
            Route::get('items/record/{item}', 'Tenant\ItemController@record');
            Route::post('items', 'Tenant\ItemController@store');
            Route::delete('items/{item}', 'Tenant\ItemController@destroy');
            Route::delete('items/item-unit-type/{item}', 'Tenant\ItemController@destroyItemUnitType');
            Route::post('items/import', 'Tenant\ItemController@import');
            Route::post('items/upload', 'Tenant\ItemController@upload');
            Route::post('items/visible_store', 'Tenant\ItemController@visibleStore');



            //Customers
//            Route::get('customers', 'Tenant\CustomerController@index')->name('tenant.customers.index');
//            Route::get('customers/columns', 'Tenant\CustomerController@columns');
//            Route::get('customers/records', 'Tenant\CustomerController@records');
//            Route::get('customers/tables', 'Tenant\CustomerController@tables');
//            Route::get('customers/record/{item}', 'Tenant\CustomerController@record');
//            Route::post('customers', 'Tenant\CustomerController@store');
//            Route::delete('customers/{customer}', 'Tenant\CustomerController@destroy');
//
//            //Suppliers
//            Route::get('suppliers', 'Tenant\SupplierController@index')->name('tenant.suppliers.index');
//            Route::get('suppliers/columns', 'Tenant\SupplierController@columns');
//            Route::get('suppliers/records', 'Tenant\SupplierController@records');
//            Route::get('suppliers/tables', 'Tenant\SupplierController@tables');
//            Route::get('suppliers/record/{item}', 'Tenant\SupplierController@record');
//            Route::post('suppliers', 'Tenant\SupplierController@store');
//            Route::delete('suppliers/{supplier}', 'Tenant\SupplierController@destroy');

            //Persons
            Route::get('persons/columns', 'Tenant\PersonController@columns');
            Route::get('persons/tables', 'Tenant\PersonController@tables');
            Route::get('persons/{type}', 'Tenant\PersonController@index')->name('tenant.persons.index');
            Route::get('persons/{type}/records', 'Tenant\PersonController@records');
            Route::get('persons/record/{person}', 'Tenant\PersonController@record');
            Route::post('persons', 'Tenant\PersonController@store');
            Route::delete('persons/{person}', 'Tenant\PersonController@destroy');
            Route::post('persons/import', 'Tenant\PersonController@import');

            //Documents
            Route::get('documents/search/customers', 'Tenant\DocumentController@searchCustomers');
            Route::get('documents/search/customer/{id}', 'Tenant\DocumentController@searchCustomerById');

            Route::get('documents', 'Tenant\DocumentController@index')->name('tenant.documents.index');
            Route::get('documents/columns', 'Tenant\DocumentController@columns');
            Route::get('documents/records', 'Tenant\DocumentController@records');
            Route::get('documents/create', 'Tenant\DocumentController@create')->name('tenant.documents.create');
            Route::get('documents/create_tensu', 'Tenant\DocumentController@create_tensu')->name('tenant.documents.create_tensu');

            Route::get('documents/tables', 'Tenant\DocumentController@tables');
            Route::get('documents/record/{document}', 'Tenant\DocumentController@record');
            Route::post('documents', 'Tenant\DocumentController@store');
            Route::get('documents/send/{document}', 'Tenant\DocumentController@send');
            Route::get('documents/consult_cdr/{document}', 'Tenant\DocumentController@consultCdr');
            Route::post('documents/email', 'Tenant\DocumentController@email');
            Route::get('documents/note/{document}', 'Tenant\NoteController@create');
            Route::get('documents/note/record/{document}', 'Tenant\NoteController@record');
            Route::get('documents/item/tables', 'Tenant\DocumentController@item_tables');
            Route::get('documents/table/{table}', 'Tenant\DocumentController@table');
            Route::get('documents/re_store/{document}', 'Tenant\DocumentController@reStore');
            Route::get('documents/locked_emission', 'Tenant\DocumentController@messageLockedEmission');

           Route::get('document_payments/records/{document_id}', 'Tenant\DocumentPaymentController@records');
           Route::get('document_payments/document/{document_id}', 'Tenant\DocumentPaymentController@document');
           Route::get('document_payments/tables', 'Tenant\DocumentPaymentController@tables');
           Route::post('document_payments', 'Tenant\DocumentPaymentController@store');
           Route::delete('document_payments/{document_payment}', 'Tenant\DocumentPaymentController@destroy');
           Route::get('document_payments/initialize_balance', 'Tenant\DocumentPaymentController@initialize_balance');

            Route::get('documents/send_server/{document}/{query?}', 'Tenant\DocumentController@sendServer');
            Route::get('documents/check_server/{document}', 'Tenant\DocumentController@checkServer');
            Route::get('documents/change_to_registered_status/{document}', 'Tenant\DocumentController@changeToRegisteredStatus');

            Route::post('documents/import', 'Tenant\DocumentController@import');
            Route::post('documents/import_second_format', 'Tenant\DocumentController@importTwoFormat');
            Route::get('documents/data_table', 'Tenant\DocumentController@data_table');
            Route::get('documents/payments/excel', 'Tenant\DocumentController@report_payments')->name('tenant.document.payments.excel');

            //Contingencies
            Route::get('contingencies', 'Tenant\ContingencyController@index')->name('tenant.contingencies.index');
            Route::get('contingencies/columns', 'Tenant\ContingencyController@columns');
            Route::get('contingencies/records', 'Tenant\ContingencyController@records');
            Route::get('contingencies/create', 'Tenant\ContingencyController@create')->name('tenant.contingencies.create');


            //Summaries
            Route::get('summaries', 'Tenant\SummaryController@index')->name('tenant.summaries.index');
            Route::get('summaries/records', 'Tenant\SummaryController@records');
            Route::post('summaries/documents', 'Tenant\SummaryController@documents');
            Route::post('summaries', 'Tenant\SummaryController@store');
            Route::get('summaries/status/{summary}', 'Tenant\SummaryController@status');
            Route::get('summaries/columns', 'Tenant\SummaryController@columns');
            Route::delete('summaries/{summary}', 'Tenant\SummaryController@destroy');

           //Inventories
        //    Route::get('inventories', 'Tenant\InventoryController@index')->name('tenant.inventories.index');
        //    Route::get('inventories/records', 'Tenant\InventoryController@records');
        //    Route::get('inventories/columns', 'Tenant\InventoryController@columns');
        //    Route::get('inventories/tables', 'Tenant\InventoryController@tables');
        //    Route::get('inventories/record/{inventory}', 'Tenant\InventoryController@record');
        //    Route::post('inventories', 'Tenant\InventoryController@store');
        //    Route::post('inventories/move', 'Tenant\InventoryController@move');
        //    Route::post('inventories/remove', 'Tenant\InventoryController@remove');

            //Voided
            Route::get('voided', 'Tenant\VoidedController@index')->name('tenant.voided.index');
            Route::get('voided/columns', 'Tenant\VoidedController@columns');
            Route::get('voided/records', 'Tenant\VoidedController@records');
            Route::post('voided', 'Tenant\VoidedController@store');
//            Route::get('voided/download/{type}/{voided}', 'Tenant\VoidedController@download')->name('tenant.voided.download');
            Route::get('voided/status/{voided}', 'Tenant\VoidedController@status');
            Route::delete('voided/{voided}', 'Tenant\VoidedController@destroy');
//            Route::get('voided/ticket/{voided_id}/{group_id}', 'Tenant\VoidedController@ticket');

            //Retentions
            Route::get('retentions', 'Tenant\RetentionController@index')->name('tenant.retentions.index');
            Route::get('retentions/columns', 'Tenant\RetentionController@columns');
            Route::get('retentions/records', 'Tenant\RetentionController@records');
            Route::get('retentions/create', 'Tenant\RetentionController@create')->name('tenant.retentions.create');
            Route::get('retentions/tables', 'Tenant\RetentionController@tables');
            Route::get('retentions/record/{retention}', 'Tenant\RetentionController@record');
            Route::post('retentions', 'Tenant\RetentionController@store');
            Route::delete('retentions/{retention}', 'Tenant\RetentionController@destroy');
            Route::get('retentions/document/tables', 'Tenant\RetentionController@document_tables');
            Route::get('retentions/table/{table}', 'Tenant\RetentionController@table');

            //Dispatches
            Route::get('dispatches', 'Tenant\DispatchController@index')->name('tenant.dispatches.index');
            Route::get('dispatches/columns', 'Tenant\DispatchController@columns');
            Route::get('dispatches/records', 'Tenant\DispatchController@records');
            Route::get('dispatches/create/{document?}/{dispatch?}', 'Tenant\DispatchController@create');
            Route::post('dispatches/tables', 'Tenant\DispatchController@tables');
            Route::post('dispatches', 'Tenant\DispatchController@store');

//            Route::get('dispatches', 'Tenant\DispatchController@index')->name('tenant.dispatches.index');
//            Route::get('dispatches/columns', 'Tenant\DispatchController@columns');
//            Route::get('dispatches/records', 'Tenant\DispatchController@records');
//            Route::get('dispatches/create', 'Tenant\DispatchController@create');
//            Route::post('dispatches/tables', 'Tenant\DispatchController@tables');
//            Route::post('dispatches', 'Tenant\DispatchController@store');

            // Route::get('reports/sales', 'Tenant\ReportController@index')->name('tenant.reports.index');
            // Route::get('reports/sales/search', 'Tenant\ReportController@search')->name('tenant.search');
            // Route::post('reports/sales/pdf', 'Tenant\ReportController@pdf')->name('tenant.report_pdf');
            // Route::post('reports/sales/excel', 'Tenant\ReportController@excel')->name('tenant.report_excel');

            // Route::get('reports/purchases', 'Tenant\ReportPurchaseController@index')->name('tenant.reports.purchases.index');
            // Route::get('reports/purchases/search', 'Tenant\ReportPurchaseController@search')->name('tenant.reports.purchases.search');
            // Route::post('reports/purchases/pdf', 'Tenant\ReportPurchaseController@pdf')->name('tenant.report.purchases.pdf');
            // Route::post('reports/purchases/excel', 'Tenant\ReportPurchaseController@excel')->name('tenant.report.purchases.report_excel');

//            Route::get('reports/inventories', 'Tenant\ReportInventoryController@index')->name('tenant.reports.inventories.index');
//            Route::post('reports/inventories/search', 'Tenant\ReportInventoryController@search')->name('tenant.reports.inventories.search');
//            Route::post('reports/inventories/pdf', 'Tenant\ReportInventoryController@pdf')->name('tenant.report.inventories.pdf');
//            Route::post('reports/inventories/excel', 'Tenant\ReportInventoryController@excel')->name('tenant.report.inventories.report_excel');
//
//            Route::get('reports/kardex', 'Tenant\ReportKardexController@index')->name('tenant.reports.kardex.index');
//            Route::post('reports/kardex/search', 'Tenant\ReportKardexController@search')->name('tenant.reports.kardex.search');
//            Route::post('reports/kardex/pdf', 'Tenant\ReportKardexController@pdf')->name('tenant.report.kardex.pdf');
//            Route::post('reports/kardex/excel', 'Tenant\ReportKardexController@excel')->name('tenant.report.kardex.report_excel');

            Route::get('reports/consistency-documents', 'Tenant\ReportConsistencyDocumentController@index')->name('tenant.consistency-documents.index');
            Route::post('reports/consistency-documents/lists', 'Tenant\ReportConsistencyDocumentController@lists');

            Route::post('options/delete_documents', 'Tenant\OptionController@deleteDocuments');

            Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
            Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');
            Route::post('services/exchange_rate', 'Tenant\Api\ServiceController@exchange_rate');
            Route::post('services/search_exchange_rate', 'Tenant\Api\ServiceController@searchExchangeRateByDate');
            Route::get('services/exchange_rate/{date}', 'Tenant\Api\ServiceController@exchangeRateTest');

            //BUSQUEDA DE DOCUMENTOS
            // Route::get('busqueda', 'Tenant\SearchController@index')->name('search');
            // Route::post('busqueda', 'Tenant\SearchController@index')->name('search');

            //Codes
            Route::get('codes/records', 'Tenant\Catalogs\CodeController@records');
            Route::get('codes/tables', 'Tenant\Catalogs\CodeController@tables');
            Route::get('codes/record/{code}', 'Tenant\Catalogs\CodeController@record');
            Route::post('codes', 'Tenant\Catalogs\CodeController@store');
            Route::delete('codes/{code}', 'Tenant\Catalogs\CodeController@destroy');

            //Units
            Route::get('unit_types/records', 'Tenant\UnitTypeController@records');
            Route::get('unit_types/record/{code}', 'Tenant\UnitTypeController@record');
            Route::post('unit_types', 'Tenant\UnitTypeController@store');
            Route::delete('unit_types/{code}', 'Tenant\UnitTypeController@destroy');

            //Banks
            Route::get('banks/records', 'Tenant\BankController@records');
            Route::get('banks/record/{bank}', 'Tenant\BankController@record');
            Route::post('banks', 'Tenant\BankController@store');
            Route::delete('banks/{bank}', 'Tenant\BankController@destroy');

            //Exchange Rates
            Route::get('exchange_rates/records', 'Tenant\ExchangeRateController@records');
            Route::post('exchange_rates', 'Tenant\ExchangeRateController@store');

            //Currency Types
            Route::get('currency_types/records', 'Tenant\CurrencyTypeController@records');
            Route::get('currency_types/record/{currency_type}', 'Tenant\CurrencyTypeController@record');
            Route::post('currency_types', 'Tenant\CurrencyTypeController@store');
            Route::delete('currency_types/{currency_type}', 'Tenant\CurrencyTypeController@destroy');

            //Perceptions
            Route::get('perceptions', 'Tenant\PerceptionController@index')->name('tenant.perceptions.index');
            Route::get('perceptions/columns', 'Tenant\PerceptionController@columns');
            Route::get('perceptions/records', 'Tenant\PerceptionController@records');
            Route::get('perceptions/create', 'Tenant\PerceptionController@create')->name('tenant.perceptions.create');
            Route::get('perceptions/tables', 'Tenant\PerceptionController@tables');
            Route::get('perceptions/record/{perception}', 'Tenant\PerceptionController@record');
            Route::post('perceptions', 'Tenant\PerceptionController@store');
            Route::delete('perceptions/{perception}', 'Tenant\PerceptionController@destroy');
            Route::get('perceptions/document/tables', 'Tenant\PerceptionController@document_tables');
            Route::get('perceptions/table/{table}', 'Tenant\PerceptionController@table');


            //Tribute Concept Type
            Route::get('tribute_concept_types/records', 'Tenant\TributeConceptTypeController@records');
            Route::get('tribute_concept_types/record/{id}', 'Tenant\TributeConceptTypeController@record');
            Route::post('tribute_concept_types', 'Tenant\TributeConceptTypeController@store');
            Route::delete('tribute_concept_types/{id}', 'Tenant\TributeConceptTypeController@destroy');

            //purchases
            Route::get('purchases', 'Tenant\PurchaseController@index')->name('tenant.purchases.index');
            Route::get('purchases/columns', 'Tenant\PurchaseController@columns');
            Route::get('purchases/records', 'Tenant\PurchaseController@records');
            Route::get('purchases/create', 'Tenant\PurchaseController@create')->name('tenant.purchases.create');
            Route::get('purchases/tables', 'Tenant\PurchaseController@tables');
            Route::get('purchases/table/{table}', 'Tenant\PurchaseController@table');
            Route::post('purchases', 'Tenant\PurchaseController@store');
            Route::post('purchases/update', 'Tenant\PurchaseController@update');
            Route::get('purchases/record/{document}', 'Tenant\PurchaseController@record');
            Route::get('purchases/edit/{id}', 'Tenant\PurchaseController@edit');
            Route::get('purchases/anular/{id}', 'Tenant\PurchaseController@anular');
            Route::get('purchases/delete/{id}', 'Tenant\PurchaseController@delete');


            // Route::get('documents/send/{document}', 'Tenant\DocumentController@send');
            // Route::get('documents/consult_cdr/{document}', 'Tenant\DocumentController@consultCdr');
            // Route::post('documents/email', 'Tenant\DocumentController@email');
            // Route::get('documents/note/{document}', 'Tenant\NoteController@create');
            Route::get('purchases/item/tables', 'Tenant\PurchaseController@item_tables');
            // Route::get('documents/table/{table}', 'Tenant\DocumentController@table');

            //quotations
            Route::get('quotations', 'Tenant\QuotationController@index')->name('tenant.quotations.index');
            Route::get('quotations/columns', 'Tenant\QuotationController@columns');
            Route::get('quotations/records', 'Tenant\QuotationController@records');
            Route::get('quotations/create', 'Tenant\QuotationController@create')->name('tenant.quotations.create');
            Route::get('quotations/edit/{id}', 'Tenant\QuotationController@edit');

            Route::get('quotations/tables', 'Tenant\QuotationController@tables');
            Route::get('quotations/table/{table}', 'Tenant\QuotationController@table');
            Route::post('quotations', 'Tenant\QuotationController@store');
            Route::post('quotations/update', 'Tenant\QuotationController@update');
            Route::get('quotations/record/{quotation}', 'Tenant\QuotationController@record');
            Route::get('quotations/anular/{id}', 'Tenant\QuotationController@anular');
            Route::get('quotations/item/tables', 'Tenant\QuotationController@item_tables');
            Route::get('quotations/option/tables', 'Tenant\QuotationController@option_tables');
            Route::get('quotations/search/customers', 'Tenant\QuotationController@searchCustomers');
            Route::get('quotations/search/customer/{id}', 'Tenant\QuotationController@searchCustomerById');
            Route::get('quotations/download/{external_id}/{format?}', 'Tenant\QuotationController@download');
            Route::get('quotations/print/{external_id}/{format?}', 'Tenant\QuotationController@toPrint');
            Route::post('quotations/email', 'Tenant\QuotationController@email');
            Route::post('quotations/duplicate', 'Tenant\QuotationController@duplicate');
            Route::get('quotations/record2/{quotation}', 'Tenant\QuotationController@record2');

            // Route::get('reports/quotations', 'Tenant\ReportQuotationController@index')->name('tenant.reports.quotations.index');
            // Route::get('reports/quotations/search', 'Tenant\ReportQuotationController@search')->name('tenant.reports.quotations.search');
            // Route::post('reports/quotations/pdf', 'Tenant\ReportQuotationController@pdf')->name('tenant.reports.quotations.pdf');
            // Route::post('reports/quotations/pdf', 'Tenant\ReportQuotationController@pdf')->name('tenant.reports.quotations.pdf');
            // Route::post('reports/quotations/excel', 'Tenant\ReportQuotationController@excel')->name('tenant.report.quotations.report_excel');





            //sale-notes
            Route::get('sale-notes', 'Tenant\SaleNoteController@index')->name('tenant.sale_notes.index');
            Route::get('sale-notes/columns', 'Tenant\SaleNoteController@columns');
            Route::get('sale-notes/records', 'Tenant\SaleNoteController@records');
            // Route::get('sale-notes/create', 'Tenant\SaleNoteController@create')->name('tenant.sale_notes.create');
            Route::get('sale-notes/create/{salenote?}', 'Tenant\SaleNoteController@create')->name('tenant.sale_notes.create');

            Route::get('sale-notes/tables', 'Tenant\SaleNoteController@tables');
            Route::get('sale-notes/table/{table}', 'Tenant\SaleNoteController@table');
            Route::post('sale-notes', 'Tenant\SaleNoteController@store');
            Route::get('sale-notes/record/{salenote}', 'Tenant\SaleNoteController@record');
            Route::get('sale-notes/item/tables', 'Tenant\SaleNoteController@item_tables');
            Route::get('sale-notes/search/customers', 'Tenant\SaleNoteController@searchCustomers');
            Route::get('sale-notes/search/customer/{id}', 'Tenant\SaleNoteController@searchCustomerById');
            Route::get('sale-notes/print/{external_id}/{format?}', 'Tenant\SaleNoteController@toPrint');
            Route::get('sale-notes/record2/{salenote}', 'Tenant\SaleNoteController@record2');
            Route::get('sale-notes/option/tables', 'Tenant\SaleNoteController@option_tables');
            Route::get('sale-notes/changed/{salenote}', 'Tenant\SaleNoteController@changed');
            Route::post('sale-notes/email', 'Tenant\SaleNoteController@email');
         //   Route::get('sale-notes/recreate_pdf/{sale_note}', 'Tenant\SaleNoteController@recreatePdf');
            // Route::get('sale-notes/print/{sale_note_id}/{format}', 'Tenant\SaleNotePaymentController@toPrint');
            // Route::get('sale-notes/print/{sale_note_id}', 'Tenant\SaleNotePaymentController@toPrint');
            Route::get('sale-notes/print-a5/{sale_note_id}/{format}', 'Tenant\SaleNotePaymentController@toPrint');
            Route::get('sale-notes/dispatches', 'Tenant\SaleNoteController@dispatches');

            // Route::get('reports/sale-notes', 'Tenant\ReportSaleNoteController@index')->name('tenant.reports.sale_note.index');
            // Route::get('reports/sale-notes/search', 'Tenant\ReportSaleNoteController@search')->name('tenant.reports.sale_note.search');
            // Route::post('reports/sale-notes/pdf', 'Tenant\ReportSaleNoteController@pdf')->name('tenant.reports.sale_note.pdf');
            // Route::post('reports/sale-notes/pdf', 'Tenant\ReportSaleNoteController@pdf')->name('tenant.reports.sale_note.pdf');
            // Route::post('reports/sale-notes/excel', 'Tenant\ReportSaleNoteController@excel')->name('tenant.report.sale_note.report_excel');

           Route::get('sale_note_payments/records/{sale_note}', 'Tenant\SaleNotePaymentController@records');
           Route::get('sale_note_payments/document/{sale_note}', 'Tenant\SaleNotePaymentController@document');
           Route::get('sale_note_payments/tables', 'Tenant\SaleNotePaymentController@tables');
           Route::post('sale_note_payments', 'Tenant\SaleNotePaymentController@store');
           Route::delete('sale_note_payments/{sale_note_payment}', 'Tenant\SaleNotePaymentController@destroy');



           //POS
           Route::get('pos', 'Tenant\PosController@index')->name('tenant.pos.index');
           Route::get('pos/search_items', 'Tenant\PosController@search_items');
           Route::get('pos/tables', 'Tenant\PosController@tables');
           Route::get('pos/table/{table}', 'Tenant\PosController@table');
           Route::get('pos/payment_tables', 'Tenant\PosController@payment_tables');
           Route::get('pos/payment', 'Tenant\PosController@payment')->name('tenant.pos.payment');
           Route::get('pos/status_configuration', 'Tenant\PosController@status_configuration');
           Route::get('pos/validate_stock/{item}/{quantity}', 'Tenant\PosController@validate_stock');


           Route::get('cash', 'Tenant\CashController@index')->name('tenant.cash.index');
           Route::get('cash/columns', 'Tenant\CashController@columns');
           Route::get('cash/records', 'Tenant\CashController@records');
           Route::get('cash/create', 'Tenant\CashController@create')->name('tenant.sale_notes.create');
           Route::get('cash/tables', 'Tenant\CashController@tables');
           Route::get('cash/opening_cash', 'Tenant\CashController@opening_cash');
           Route::get('cash/opening_cash_check/{user_id}', 'Tenant\CashController@opening_cash_check');

           Route::post('cash', 'Tenant\CashController@store');
           Route::post('cash/cash_document', 'Tenant\CashController@cash_document');
           Route::get('cash/close/{cash}', 'Tenant\CashController@close');
           Route::get('cash/report/{cash}', 'Tenant\CashController@report');
           Route::get('cash/record/{cash}', 'Tenant\CashController@record');
           Route::delete('cash/{cash}', 'Tenant\CashController@destroy');
           Route::get('cash/item/tables', 'Tenant\CashController@item_tables');
           Route::get('cash/search/customers', 'Tenant\CashController@searchCustomers');
           Route::get('cash/search/customer/{id}', 'Tenant\CashController@searchCustomerById');

           //Tags
           Route::get('tags', 'Tenant\TagController@index')->name('tenant.tags.index');
           Route::get('tags/columns', 'Tenant\TagController@columns');
           Route::get('tags/records', 'Tenant\TagController@records');
           Route::get('tags/record/{tag}', 'Tenant\TagController@record');
           Route::post('tags', 'Tenant\TagController@store');
           Route::delete('tags/{tag}', 'Tenant\TagController@destroy');

           //Promotion
           Route::get('promotions', 'Tenant\PromotionController@index')->name('tenant.promotion.index');
           Route::get('promotions/columns', 'Tenant\PromotionController@columns');
           Route::get('promotions/tables', 'Tenant\PromotionController@tables');
           Route::get('promotions/records', 'Tenant\PromotionController@records');
           Route::get('promotions/record/{tag}', 'Tenant\PromotionController@record');
           Route::post('promotions', 'Tenant\PromotionController@store');
           Route::delete('promotions/{promotion}', 'Tenant\PromotionController@destroy');
           Route::post('promotions/upload', 'Tenant\PromotionController@upload');


           Route::get('item-sets', 'Tenant\ItemSetController@index')->name('tenant.item_sets.index');
           Route::get('item-sets/columns', 'Tenant\ItemSetController@columns');
           Route::get('item-sets/records', 'Tenant\ItemSetController@records');
           Route::get('item-sets/tables', 'Tenant\ItemSetController@tables');
           Route::get('item-sets/record/{item}', 'Tenant\ItemSetController@record');
           Route::post('item-sets', 'Tenant\ItemSetController@store');
           Route::delete('item-sets/{item}', 'Tenant\ItemSetController@destroy');
           Route::delete('item-sets/item-unit-type/{item}', 'Tenant\ItemSetController@destroyItemUnitType');
           Route::post('item-sets/import', 'Tenant\ItemSetController@import');
           Route::post('item-sets/upload', 'Tenant\ItemSetController@upload');
           Route::post('item-sets/visible_store', 'Tenant\ItemSetController@visibleStore');

           Route::get('person-types/columns', 'Tenant\PersonTypeController@columns');
           Route::get('person-types', 'Tenant\PersonTypeController@index')->name('tenant.person_types.index');
           Route::get('person-types/records', 'Tenant\PersonTypeController@records');
           Route::get('person-types/record/{person}', 'Tenant\PersonTypeController@record');
           Route::post('person-types', 'Tenant\PersonTypeController@store');
           Route::delete('person-types/{person}', 'Tenant\PersonTypeController@destroy');

           //Cuenta
           Route::get('cuenta/payment_index', 'Tenant\AccountController@paymentIndex')->name('tenant.payment.index');
           Route::get('cuenta/configuration', 'Tenant\AccountController@index')->name('tenant.configuration.index');
           Route::get('cuenta/payment_records', 'Tenant\AccountController@paymentRecords');
           Route::get('cuenta/tables', 'Tenant\AccountController@tables');
           Route::post('cuenta/update_plan', 'Tenant\AccountController@updatePlan');
           Route::post('cuenta/payment_culqui', 'Tenant\AccountController@paymentCulqui')->name('tenant.account.payment_culqui');

        });
    });
} else {
    Route::domain(env('APP_URL_BASE'))->group(function() {

        Route::get('login', 'System\LoginController@showLoginForm')->name('login');
        Route::post('login', 'System\LoginController@login');
        Route::post('logout', 'System\LoginController@logout')->name('logout');

        Route::middleware('auth:admin')->group(function() {
            Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
            Route::get('/', function () {
                return redirect()->route('system.dashboard');
            });
            Route::get('dashboard', 'System\HomeController@index')->name('system.dashboard');

            //Clients
            Route::get('clients', 'System\ClientController@index')->name('system.clients.index');
            Route::get('clients/records', 'System\ClientController@records');
            Route::get('clients/record/{client}', 'System\ClientController@record');

            Route::get('clients/create', 'System\ClientController@create');
            Route::get('clients/tables', 'System\ClientController@tables');
            Route::get('clients/charts', 'System\ClientController@charts');
            Route::post('clients', 'System\ClientController@store');
            Route::post('clients/update', 'System\ClientController@update');

            Route::delete('clients/{client}', 'System\ClientController@destroy');
            Route::post('clients/password/{client}', 'System\ClientController@password');
            Route::post('clients/locked_emission', 'System\ClientController@lockedEmission');
            Route::post('clients/locked_tenant', 'System\ClientController@lockedTenant');
            Route::post('clients/locked_user', 'System\ClientController@lockedUser');
            Route::post('clients/renew_plan', 'System\ClientController@renewPlan');


            Route::post('clients/set_billing_cycle', 'System\ClientController@startBillingCycle');

            Route::post('clients/locked_tenant', 'System\ClientController@lockedTenant');



            Route::get('client_payments/records/{client_id}', 'System\ClientPaymentController@records');
            Route::get('client_payments/client/{client_id}', 'System\ClientPaymentController@client');
            Route::get('client_payments/tables', 'System\ClientPaymentController@tables');
            Route::post('client_payments', 'System\ClientPaymentController@store');
            Route::delete('client_payments/{client_payment}', 'System\ClientPaymentController@destroy');
            Route::get('client_payments/cancel_payment/{client_payment_id}', 'System\ClientPaymentController@cancel_payment');


            Route::get('client_account_status/records/{client_id}', 'System\AccountStatusController@records');
            Route::get('client_account_status/client/{client_id}', 'System\AccountStatusController@client');
            Route::get('client_account_status/tables', 'System\AccountStatusController@tables');

            //Planes
            Route::get('plans', 'System\PlanController@index')->name('system.plans.index');
            Route::get('plans/records', 'System\PlanController@records');
            Route::get('plans/tables', 'System\PlanController@tables');
            Route::get('plans/record/{plan}', 'System\PlanController@record');
            Route::post('plans', 'System\PlanController@store');
            Route::delete('plans/{plan}', 'System\PlanController@destroy');

            //Users
            Route::get('users/create', 'System\UserController@create')->name('system.users.create');
            Route::get('users/record', 'System\UserController@record');
            Route::post('users', 'System\UserController@store');

            Route::get('services/ruc/{number}', 'System\ServiceController@ruc');


        });
    });
}
