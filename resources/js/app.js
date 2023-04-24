require('./bootstrap');

import Vue from 'vue'
import store from './store'
import ElementUI from 'element-ui'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'

locale.use(lang)

ElementUI.Select.computed.readonly = function () {
    const isIE = !this.$isServer && !Number.isNaN(Number(document.documentMode));
    return !(this.filterable || this.multiple || !isIE) && !this.visible;
};

export default ElementUI;

Vue.use(ElementUI, { size: 'small' })
Vue.prototype.$eventHub = new Vue()

Vue.component('tenant-item-aditional-info-selector', require('./views/tenant/components/partials/item_extra_info.vue'));
Vue.component('tenant-item-aditional-info-modal', require('./views/tenant/components/partials/modal_item_info_attributes.vue'));


Vue.component('tenant-dashboard-index', require('../../modules/Dashboard/Resources/assets/js/views/index.vue'));
Vue.component('tenant-dashboard-sales-by-product', require('../../modules/Dashboard/Resources/assets/js/views/items/SalesByProduct.vue'));

Vue.component('x-graph', require('./components/graph/src/Graph.vue'));
Vue.component('x-graph-line', require('./components/graph/src/GraphLine.vue'));

// configuracion pse
Vue.component('tenant-signature-pse-index', require('./views/tenant/companies/signature_pse/index.vue'))
Vue.component('tenant-whatsapp-api-index', require('./views/tenant/companies/whatsapp_api/index.vue'))

Vue.component('tenant-companies-form', require('./views/tenant/companies/form.vue'));
Vue.component('tenant-companies-logo', require('./views/tenant/companies/logo.vue'));
Vue.component('tenant-certificates-index', require('./views/tenant/certificates/index.vue'));
Vue.component('tenant-certificates-form', require('./views/tenant/certificates/form.vue'));
Vue.component('tenant-configurations-form', require('./views/tenant/configurations/form.vue'));
Vue.component('tenant-configurations-form-purchases', require('./views/tenant/configurations/partials/purchases.vue'));
Vue.component('tenant-configurations-visual', require('./views/tenant/configurations/visual.vue'));
Vue.component('tenant-configurations-pdf', require('./views/tenant/configurations/pdf_templates.vue'));
Vue.component('tenant-configurations-ticket-pdf', require('./views/tenant/configurations/pdf_ticket_templates.vue'));
Vue.component('tenant-configurations-sale-notes', require('./views/tenant/configurations/sale_notes.vue'));
Vue.component('tenant-configurations-pdf-guide', require('./views/tenant/configurations/pdf_guide_templates.vue'));
Vue.component('tenant-configurations-preprinted-pdf', require('./views/tenant/configurations/pdf_preprinted_templates.vue'));
Vue.component('tenant-dialog-header-menu', require('./views/tenant/configurations/partials/dialog_header_menu.vue'));
// Vue.component('tenant-establishments-form', require('./views/tenant/establishments/form.vue'));
// Vue.component('tenant-series-form', require('./views/tenant/series/form.vue'));
Vue.component('tenant-bank_accounts-index', require('./views/tenant/bank_accounts/index.vue'));
Vue.component('tenant-items-index', require('./views/tenant/items/index.vue'));
Vue.component('tenant-persons-index', require('./views/tenant/persons/index.vue'));
// Vue.component('tenant-customers-index', require('./views/tenant/customers/index.vue'));
 Vue.component('tenant-person-form', require('./views/tenant/persons/form.vue'));

// Vue.component('tenant-suppliers-index', require('./views/tenant/suppliers/index.vue'));
Vue.component('tenant-users-form', require('./views/tenant/users/form.vue'));
Vue.component('tenant-documents-index', require('./views/tenant/documents/index.vue'));
Vue.component('tenant-documents-invoice', require('./views/tenant/documents/invoice.vue'));
Vue.component('tenant-documents-invoice-generate', require('./views/tenant/documents/invoice_generate'));
Vue.component('tenant-documents-invoicetensu', require('./views/tenant/documents/invoicetensu.vue'));
Vue.component('tenant-documents-note', require('./views/tenant/documents/note.vue'));

// purchase-settlements
Vue.component('tenant-purchase-settlements-index', require('./views/tenant/purchase-settlements/index.vue'));
Vue.component('tenant-purchase-settlements-form', require('./views/tenant/purchase-settlements/form.vue'));

Vue.component('tenant-documents-items-list', require('./views/tenant/documents/partials/item.vue'));
Vue.component('tenant-summaries-index', require('./views/tenant/summaries/index.vue'));
Vue.component('tenant-voided-index', require('./views/tenant/voided/index.vue'));
Vue.component('tenant-search-index', require('./views/tenant/search/index.vue'));
Vue.component('tenant-options-form', require('./views/tenant/options/form.vue'));
Vue.component('tenant-unit_types-index', require('./views/tenant/unit_types/index.vue'));
Vue.component('tenant-detraction_types-index', require('./views/tenant/detraction_types/index.vue'));
Vue.component('tenant-users-index', require('./views/tenant/users/index.vue'));
Vue.component('tenant-establishments-index', require('./views/tenant/establishments/index.vue'));
Vue.component('tenant-charge_discounts-index', require('./views/tenant/charge_discounts/index.vue'));
Vue.component('tenant-banks-index', require('./views/tenant/banks/index.vue'));
Vue.component('tenant-exchange_rates-index', require('./views/tenant/exchange_rates/index.vue'));
Vue.component('tenant-currency-types-index', require('./views/tenant/currency_types/index.vue'));
Vue.component('tenant-retentions-index', require('./views/tenant/retentions/index.vue'));
Vue.component('tenant-retentions-form', require('./views/tenant/retentions/form.vue'));
Vue.component('tenant-perceptions-index', require('./views/tenant/perceptions/index.vue'));
Vue.component('tenant-perceptions-form', require('./views/tenant/perceptions/form.vue'));
Vue.component('tenant-dispatches-index', require('./views/tenant/dispatches/index.vue'));

Vue.component('tenant-dispatches-form', require('./views/tenant/dispatches/form.vue'));
Vue.component('tenant-dispatches-create', require('./views/tenant/dispatches/create.vue'));
Vue.component('tenant-guides-modal', require('./views/tenant/components/guides.vue'));
Vue.component('tenant-purchases-index', require('./views/tenant/purchases/index.vue'));
Vue.component('tenant-purchases-form', require('./views/tenant/purchases/form.vue'));
Vue.component('tenant-purchases-edit', require('./views/tenant/purchases/form_edit.vue'));
Vue.component('tenant-transfer-reason-types-index', require('./views/tenant/transfer_reason_types/index.vue'));

Vue.component('tenant-dispatch_carrier-index', require('./views/tenant/dispatches/Carrier/Index'));
Vue.component('tenant-dispatch_carrier-form', require('./views/tenant/dispatches/Carrier/Form'));

Vue.component('tenant-purchases-items', require('./views/tenant/dispatches/items.vue'));
Vue.component('tenant-attribute_types-index', require('./views/tenant/attribute_types/index.vue'));
Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue'));
Vue.component('tenant-warehouses', require('./views/tenant/components/warehouses.vue'));
Vue.component('tenant-calendar-quotation', require('./views/tenant/components/calendarquotations.vue'));

//Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue'));
Vue.component('tenant-product', require('./views/tenant/components/products.vue'));


Vue.component('tenant-tasks-lists', require('./views/tenant/tasks/lists.vue'));
Vue.component('tenant-tasks-form', require('./views/tenant/tasks/form.vue'));
Vue.component('tenant-reports-consistency-documents-lists', require('./views/tenant/reports/consistency-documents/lists.vue'));
Vue.component('tenant-contingencies-index', require('./views/tenant/contingencies/index.vue'));

Vue.component('tenant-quotations-index', require('./views/tenant/quotations/index.vue'));
Vue.component('tenant-quotations-form', require('./views/tenant/quotations/form.vue'));
Vue.component('tenant-quotations-edit', require('./views/tenant/quotations/form_edit.vue'));
Vue.component('tenant-quotations-item-form', require('./views/tenant/quotations/partials/item.vue'));

Vue.component('tenant-sale-notes-index', require('./views/tenant/sale_notes/index.vue'));
Vue.component('tenant-sale-notes-form', require('./views/tenant/sale_notes/form.vue'));
Vue.component('tenant-pos-index', require('./views/tenant/pos/index.vue'));
Vue.component('cash-index', require('./views/tenant/cash/index.vue'));
Vue.component('tenant-card-brands-index', require('./views/tenant/card_brands/index.vue'));
Vue.component('tenant-pos-fast', require('./views/tenant/pos/fast.vue'));
Vue.component('tenant-pos-garage', require('./views/tenant/pos/garage.vue'));

Vue.component('tenant-payment-method-index', require('./views/tenant/payment_method/index.vue'));
Vue.component('tenant-payment-method-index', require('./views/tenant/payment_method/index.vue'));



// Modules
Vue.component('inventory-index', require('../../modules/Inventory/Resources/assets/js/inventory/index.vue'));
Vue.component('inventory-transfers-index', require('../../modules/Inventory/Resources/assets/js/transfers/index.vue'));
Vue.component('warehouses-index', require('../../modules/Inventory/Resources/assets/js/warehouses/index.vue'));
Vue.component('tenant-report-kardex-index', require('../../modules/Inventory/Resources/assets/js/kardex/index.vue'));
Vue.component('tenant-inventories-form', require('../../modules/Inventory/Resources/assets/js/config/form.vue'));
Vue.component('tenant-expenses-index', require('../../modules/Expense/Resources/assets/js/views/expenses/index.vue'));
Vue.component('tenant-expenses-form', require('../../modules/Expense/Resources/assets/js/views/expenses/form.vue'));
Vue.component('tenant-account-export', require('../../modules/Account/Resources/assets/js/views/account/export.vue'));
Vue.component('tenant-account-summary-report', require('../../modules/Account/Resources/assets/js/views/summary_report/index.vue'));
Vue.component('tenant-account-format', require('../../modules/Account/Resources/assets/js/views/account/format.vue'));
Vue.component('tenant-company-accounts', require('../../modules/Account/Resources/assets/js/views/company_accounts/form.vue'));
Vue.component('tenant-ledger-accounts', require('../../modules/Account/Resources/assets/js/views/ledger_accounts/form.vue'));

Vue.component('inventory-review-index', require('@viewsModuleInventory/inventory-review/index.vue'));

//
Vue.component('tenant-inventory-report', require('../../modules/Inventory/Resources/assets/js/inventory/reports/index.vue'));
//


Vue.component('tenant-inventory-color-index', require('../../modules/Inventory/Resources/assets/js/extra_info/color/index.vue'));
Vue.component('tenant-inventory-item-units-per-package-index', require('../../modules/Inventory/Resources/assets/js/extra_info/item_units_per_package/index.vue'));
Vue.component('tenant-inventory-item-units-business', require('../../modules/Inventory/Resources/assets/js/extra_info/item_units_business/index.vue'));
Vue.component('tenant-inventory-item-package-measurements', require('../../modules/Inventory/Resources/assets/js/extra_info/item_package_measurements/index.vue'));
Vue.component('tenant-inventory-mold-cavities', require('../../modules/Inventory/Resources/assets/js/extra_info/item_mold_cavities/index.vue'));
Vue.component('tenant-inventory-mold-property', require('../../modules/Inventory/Resources/assets/js/extra_info/item_mold_property/index.vue'));

Vue.component('tenant-inventory-size-property', require('../../modules/Inventory/Resources/assets/js/extra_info/item_size/index.vue'));
Vue.component('tenant-inventory-item-status', require('../../modules/Inventory/Resources/assets/js/extra_info/item_status/index.vue'));
Vue.component('tenant-inventory-item-product-family', require('../../modules/Inventory/Resources/assets/js/extra_info/item_product_family/index.vue'));
Vue.component('tenant-inventory-extra-info-list', require('../../modules/Inventory/Resources/assets/js/extra_info/index.vue'));

Vue.component('tenant-inventory-devolutions-index', require('../../modules/Inventory/Resources/assets/js/devolutions/index.vue'));
Vue.component('tenant-inventory-devolutions-form', require('../../modules/Inventory/Resources/assets/js/devolutions/form.vue'));

Vue.component('tenant-documents-not-sent', require('../../modules/Document/Resources/assets/js/views/documents/not_sent.vue'));
Vue.component('tenant-report-purchases-index', require('../../modules/Report/Resources/assets/js/views/purchases/index.vue'));
Vue.component('tenant-report-documents-index', require('../../modules/Report/Resources/assets/js/views/documents/index.vue'));
Vue.component('tenant-state-account-index', require('../../modules/Report/Resources/assets/js/views/state_account/index.vue'));
Vue.component('tenant-report-customers-index', require('../../modules/Report/Resources/assets/js/views/customers/index.vue'));
Vue.component('tenant-report-items-index', require('../../modules/Report/Resources/assets/js/views/items/index.vue'));
Vue.component('tenant-report-items-extra-index', require('../../modules/Report/Resources/assets/js/views/items/index_extra.vue'));

Vue.component('tenant-report-download-tray-index', require('../../modules/Report/Resources/assets/js/views/download_tray/index.vue'));

/** Reporte de guias */
Vue.component('tenant-report-guide-index', require('../../modules/Report/Resources/assets/js/views/guide/index.vue'));
Vue.component('tenant-report-sale_notes-index', require('../../modules/Report/Resources/assets/js/views/sale_notes/index.vue'));
Vue.component('tenant-report-quotations-index', require('../../modules/Report/Resources/assets/js/views/quotations/index.vue'));
Vue.component('tenant-report-cash-index', require('../../modules/Report/Resources/assets/js/views/cash/index.vue'));
Vue.component('tenant-index-configuration', require('../../modules/BusinessTurn/Resources/assets/js/views/configurations/index.vue'));
Vue.component('tenant-report-document_hotels-index', require('../../modules/Report/Resources/assets/js/views/document_hotels/index.vue'));
Vue.component('tenant-report_hotels-index', require('../../modules/Report/Resources/assets/js/views/report_hotels/index.vue'));
Vue.component('tenant-report-commercial_analysis-index', require('../../modules/Report/Resources/assets/js/views/commercial_analysis/index.vue'));
Vue.component('tenant-offline-configurations-index', require('../../modules/Offline/Resources/assets/js/views/offline_configurations/index.vue'));
Vue.component('tenant-series-configurations-index', require('../../modules/Document/Resources/assets/js/views/series_configurations/index.vue'));
Vue.component('tenant-validate-documents-index', require('../../modules/Document/Resources/assets/js/views/validate_documents/index.vue'));
Vue.component('tenant-report-document-detractions-index', require('../../modules/Report/Resources/assets/js/views/document-detractions/index.vue'));
Vue.component('tenant-report-commissions-index', require('../../modules/Report/Resources/assets/js/views/commissions/index.vue'));
Vue.component('tenant-report-order-notes-consolidated-index', require('../../modules/Report/Resources/assets/js/views/order_notes_consolidated/index.vue'));
Vue.component('tenant-report-general-items-index', require('../../modules/Report/Resources/assets/js/views/general_items/index.vue'));
Vue.component('tenant-report-order-notes-general-index', require('../../modules/Report/Resources/assets/js/views/order_notes_general/index.vue'));
Vue.component('tenant-report-sales-consolidated-index', require('../../modules/Report/Resources/assets/js/views/sales_consolidated/index.vue'));
Vue.component('tenant-report-user-commissions-index', require('../../modules/Report/Resources/assets/js/views/user_commissions/index.vue'));
Vue.component('tenant-report-fixed-asset-purchases-index', require('../../modules/Report/Resources/assets/js/views/fixed-asset-purchases/index.vue'));
Vue.component('tenant-report-massive-downloads-index', require('../../modules/Report/Resources/assets/js/views/massive-downloads/index.vue'));
Vue.component('tenant-documents-regularize-shipping', require('../../modules/Document/Resources/assets/js/views/documents/regularize_shipping.vue'));
Vue.component('tenant-report-commissions-detail-index', require('../../modules/Report/Resources/assets/js/views/commissions_detail/index.vue'));

Vue.component('tenant-report-tips-index', require('../../modules/Report/Resources/assets/js/views/tips/index.vue'));

Vue.component('tenant-categories-index', require('../../modules/Item/Resources/assets/js/views/categories/index.vue'));
Vue.component('tenant-brands-index', require('../../modules/Item/Resources/assets/js/views/brands/index.vue'));
Vue.component('tenant-zone-index', require('../../modules/Item/Resources/assets/js/views/zone/index.vue'));
Vue.component('tenant-incentives-index', require('../../modules/Item/Resources/assets/js/views/incentives/index.vue'));
Vue.component('tenant-item-lots-index', require('../../modules/Item/Resources/assets/js/views/item-lots/index.vue'));

Vue.component('tenant-ecommerce-configuration-info', require('../../modules/Ecommerce/Resources/assets/js/views/configuration/index.vue'));
Vue.component('tenant-ecommerce-configuration-culqi', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_culqi/index.vue'));
Vue.component('tenant-ecommerce-configuration-paypal', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_paypal/index.vue'));
Vue.component('tenant-ecommerce-configuration-logo', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_logo/index.vue'));
Vue.component('tenant-ecommerce-configuration-social', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_social/index.vue'));
Vue.component('tenant-ecommerce-configuration-tag', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_tags/index.vue'));
Vue.component('tenant-ecommerce-item-sets-index', require('../../modules/Ecommerce/Resources/assets/js/views/item_sets/index.vue'));

Vue.component('tenant-purchase-quotations-index', require('../../modules/Purchase/Resources/assets/js/views/purchase-quotations/index.vue'));
Vue.component('tenant-purchase-quotations-form', require('../../modules/Purchase/Resources/assets/js/views/purchase-quotations/form.vue'));

Vue.component('tenant-purchase-orders-index', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/index.vue'));
Vue.component('tenant-purchase-orders-form', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/form.vue'));
Vue.component('tenant-purchase-orders-generate', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/generate.vue'));

Vue.component('moves-index', require('../../modules/Inventory/Resources/assets/js/moves/index.vue'));
Vue.component('inventory-form-masive', require('../../modules/Inventory/Resources/assets/js/transfers/form_masive.vue'));

Vue.component('tenant-report-kardex-master', require('../../modules/Inventory/Resources/assets/js/kardex_master/index.vue'));
Vue.component('tenant-report-kardex-lots', require('../../modules/Inventory/Resources/assets/js/kardex/lots.vue'));
Vue.component('tenant-report-kardex-series', require('../../modules/Inventory/Resources/assets/js/kardex/series.vue'));

Vue.component('tenant-order-notes-index', require('../../modules/Order/Resources/assets/js/views/order_notes/index.vue'));
Vue.component('tenant-order-notes-form', require('../../modules/Order/Resources/assets/js/views/order_notes/form.vue'));
Vue.component('tenant-order-notes-edit', require('../../modules/Order/Resources/assets/js/views/order_notes/form_edit.vue'));
Vue.component('tenant-report-valued-kardex', require('../../modules/Inventory/Resources/assets/js/valued_kardex/index.vue'));
Vue.component('tenant-mitiendape-config', require('../../modules/Order/Resources/assets/js/views/mi_tienda_pe/form.vue'));


//Finance
Vue.component('tenant-finance-global-payments-index', require('../../modules/Finance/Resources/assets/js/views/global_payments/index.vue'));
Vue.component('tenant-finance-balance-index', require('../../modules/Finance/Resources/assets/js/views/balance/index.vue'));
Vue.component('tenant-finance-modal-transfer-between-accounts', require('../../modules/Finance/Resources/assets/js/views/transfer_between_accounts/options.vue'));

Vue.component('tenant-finance-payment-method-types-index', require('../../modules/Finance/Resources/assets/js/views/payment_method_types/index.vue'));
Vue.component('tenant-finance-unpaid-index', require('@viewsModuleFinance/unpaid/index.vue'));
Vue.component('tenant-finance-to-pay-index', require('@viewsModuleFinance/to_pay/index.vue'));
Vue.component('tenant-finance-income-index', require('@viewsModuleFinance/income/index.vue'));
Vue.component('tenant-finance-income-form', require('@viewsModuleFinance/income/form.vue'));
Vue.component('tenant-income-types-index', require('@viewsModuleFinance/income_types/index.vue'));
Vue.component('tenant-income-reasons-index', require('@viewsModuleFinance/income_reasons/index.vue'));
Vue.component('tenant-finance-movements-index', require('@viewsModuleFinance/movements/index.vue'));


//Sale
Vue.component('tenant-sale-opportunities-index', require('@viewsModuleSale/sale_opportunities/index.vue'));
Vue.component('tenant-sale-opportunities-form', require('@viewsModuleSale/sale_opportunities/form.vue'));
Vue.component('tenant-payment-method-types-index', require('@viewsModuleSale/payment_method_types/index.vue'));
Vue.component('tenant-contracts-index', require('@viewsModuleSale/contracts/index.vue'));
Vue.component('tenant-contracts-form', require('@viewsModuleSale/contracts/form.vue'));
Vue.component('tenant-production-orders-index', require('@viewsModuleSale/production_orders/index.vue'));
Vue.component('tenant-agents-index', require('@viewsModuleSale/agents/index.vue'));

//Item
Vue.component('tenant-web-platforms-index', require('@viewsModuleItem/web-platforms/index.vue'));

//technical Services
Vue.component('tenant-technical-services-index', require('@viewsModuleSale/technical-services/index.vue'));
Vue.component('tenant-user-commissions-index', require('@viewsModuleSale/user-commissions/index.vue'));

//Purchase

Vue.component('tenant-fixed-asset-items-index', require('@viewsModulePurchase/fixed_asset_items/index.vue'));
Vue.component('tenant-fixed-asset-purchases-index', require('@viewsModulePurchase/fixed_asset_purchases/index.vue'));
Vue.component('tenant-fixed-asset-purchases-form', require('@viewsModulePurchase/fixed_asset_purchases/form.vue'));

//Expense

Vue.component('tenant-expense-types-index', require('@viewsModuleExpense/expense_types/index.vue'));
Vue.component('tenant-expense-reasons-index', require('@viewsModuleExpense/expense_reasons/index.vue'));
Vue.component('tenant-expense-method-types-index', require('@viewsModuleExpense/expense_method_types/index.vue'));


Vue.component('tenant-drivers-index', require('./views/tenant/dispatches/drivers/index.vue'));
Vue.component('tenant-dispatchers-index', require('./views/tenant/dispatches/dispatchers/index.vue'));
Vue.component('tenant-transports-index', require('./views/tenant/dispatches/transports/index.vue'));
Vue.component('tenant-origin_addresses-index', require('./views/tenant/dispatches/OriginAddress/Index'));

Vue.component('tenant-order-forms-index', require('@viewsModuleOrder/order_forms/index.vue'));
Vue.component('tenant-order-forms-form', require('@viewsModuleOrder/order_forms/form.vue'));


// System
Vue.component('system-clients-index', require('./views/system/clients/index.vue'));
Vue.component('system-clients-form', require('./views/system/clients/form.vue'));
Vue.component('system-users-form', require('./views/system/users/form.vue'));

Vue.component('system-certificate-index', require('./views/system/certificate/index.vue'));
Vue.component('system-companies-form', require('./views/system/companies/form.vue'));

Vue.component('system-accounting-index', require('@viewsModuleAccount/system/accounting/index.vue'));

// Hoteles :: Tarifas
Vue.component('tenant-hotel-rates', require('@viewsModuleHotel/rates/List.vue'));
// Hoteles :: Categorías
Vue.component('tenant-hotel-categories', require('@viewsModuleHotel/categories/List.vue'));
// Hoteles :: Pisos
Vue.component('tenant-hotel-floors', require('@viewsModuleHotel/floors/List.vue'));
// Hoteles :: Habitaciones
Vue.component('tenant-hotel-rooms', require('@viewsModuleHotel/rooms/List.vue'));
// Hoteles :: Recepción
Vue.component('tenant-hotel-reception', require('@viewsModuleHotel/rooms/Reception.vue'));
// Hoteles :: Rentar habitación
Vue.component('tenant-hotel-rent', require('@viewsModuleHotel/rooms/Rent.vue'));
// Hoteles :: Agregar producto a la habitación rentada
Vue.component('tenant-hotel-rent-add-product', require('@viewsModuleHotel/rooms/AddProductToRoom.vue'));
// Hoteles :: Checkout
Vue.component('tenant-hotel-rent-checkout', require('@viewsModuleHotel/rooms/Checkout.vue'));

// Trámite documentario
Vue.component('tenant-documentary-offices', require('@viewsModuleDocumentary/offices/Offices.vue'));
Vue.component('tenant-documentary-status', require('@viewsModuleDocumentary/status/Status.vue'));
Vue.component('tenant-documentary-processes', require('@viewsModuleDocumentary/processes/Processes.vue'));
Vue.component('tenant-documentary-documents', require('@viewsModuleDocumentary/documents/Documents.vue'));
Vue.component('tenant-documentary-actions', require('@viewsModuleDocumentary/actions/Actions.vue'));
Vue.component('tenant-documentary-files', require('@viewsModuleDocumentary/files/Files.vue'));
Vue.component('tenant-documentary-requirements', require('@viewsModuleDocumentary/requirements/Requirements.vue'));
Vue.component('tenant-documentary-statistic', require('@viewsModuleDocumentary/statistic/Index.vue'));

// Trámite documentario Simlpificado
Vue.component('tenant-documentary-files-simplify', require('@viewsModuleDocumentary/files_simplify/Files.vue'));
Vue.component('tenant-documentary-files-simplify-form', require('@viewsModuleDocumentary/files_simplify/FilesNew.vue'));

Vue.component('system-plans-index', require('./views/system/plans/index.vue'));
Vue.component('system-plans-form', require('./views/system/plans/form.vue'));

Vue.component('x-input-service', require('../../modules/ApiPeruDev/Resources/assets/js/components/InputService.vue')); // apiperu - porque cambiar el input si tiene el mismo contenido?
// Vue.component('x-input-service', require('./components/InputService.vue'));



Vue.component('tenant-items-ecommerce-index', require('./views/tenant/items_ecommerce/index.vue'));
Vue.component('tenant-ecommerce-cart', require('./views/tenant/ecommerce/cart_dropdown.vue'));
Vue.component('tenant-tags-index', require('./views/tenant/tags/index.vue'));
Vue.component('tenant-promotions-index', require('./views/tenant/promotions/index.vue'));

Vue.component('tenant-item-sets-index', require('./views/tenant/item_sets/index.vue'));
Vue.component('tenant-person-types-index', require('./views/tenant/person_types/index.vue'));

Vue.component('tenant-orders-index', require('./views/tenant/orders/index.vue'));

//Cuenta
Vue.component('tenant-account-payment-index', require('./views/tenant/account/payment_index.vue'));
Vue.component('tenant-account-configuration-index', require('./views/tenant/account/configuration.vue'));

//auto update
Vue.component('system-update', require('./views/system/update/index.vue'));

//auto update
Vue.component('system-backup', require('./views/system/backup/index.vue'));

//culqi
Vue.component('system-configuration-culqi', require('./views/system/configuration/culqi.vue'))

//apk url
Vue.component('system-configuration-apk-url', require('./views/system/configuration/apk-url.vue'))

//token
Vue.component('system-configuration-token', require('./views/system/configuration/token_ruc_dni.vue'))

// php info
Vue.component('system-php-configuration', require('./views/system/configuration/php_info.vue'))
Vue.component('system-server-status', require('./views/system/configuration/server_status.vue'))

//Configuración global del login
Vue.component('system-login-settings', require('./views/system/configuration/login.vue'))

Vue.component('system-login-other-configuration', require('./views/system/configuration/other_configuration.vue'))

// Configuración del login
Vue.component('tenant-login-page', require('./views/tenant/login/index.vue'))

/** Modulo DIGEMID **/
Vue.component('tenant-digemid-index', require('../../modules/Digemid/Resources/assets/js/view/index.vue'));

/** Modulo Suscripcion Escolar**/
Vue.component('tenant-suscription-client-index', require('../../modules/Suscription/Resources/assets/js/clients/index.vue'));
Vue.component('tenant-suscription-plans-index', require('../../modules/Suscription/Resources/assets/js/plans/index.vue'));
Vue.component('tenant-suscription-payments-index', require('../../modules/Suscription/Resources/assets/js/payments/index.vue'));
Vue.component('data-table-payment-receipt', require('../js/components/DataTablePaymentReceipt.vue') );
Vue.component('tenant-index-payment-receipt', require('../../modules/Suscription/Resources/assets/js/payment_receipt/index.vue') );

// Grados y secciones
Vue.component('tenant-suscription-grades-index', require('@viewsModuleSuscription/grades/index.vue') );
Vue.component('tenant-suscription-sections-index', require('@viewsModuleSuscription/sections/index.vue') );


/** Modulo Suscripcion **/
Vue.component('tenant-full-suscription-client-index', require('../../modules/FullSuscription/Resources/assets/js/clients/index.vue'));
Vue.component('tenant-full-suscription-plans-index', require('../../modules/FullSuscription/Resources/assets/js/plans/index.vue'));
Vue.component('tenant-full-suscription-payments-index', require('../../modules/FullSuscription/Resources/assets/js/payments/index.vue'));
Vue.component('tenant-full-suscription-index-payment-receipt', require('../../modules/FullSuscription/Resources/assets/js/payment_receipt/index.vue') );


/** Prestamos Bancarios **/
Vue.component('tenant-bankloans-index', require('../../modules/Expense/Resources/assets/js/views/bank_loans/index.vue'));
Vue.component('tenant-bankloans-form', require('../../modules/Expense/Resources/assets/js/views/bank_loans/form.vue'));

/**Molino */
Vue.component('tenant-mill-index', require('../../modules/Production/Resources/assets/js/view/mill/index.vue'));
Vue.component('tenant-mill-form', require('../../modules/Production/Resources/assets/js/view/mill/form.vue'));
/** Maquinaria */
Vue.component('tenant-machine-index', require('../../modules/Production/Resources/assets/js/view/machine/index.vue'));
Vue.component('tenant-machine-type-index', require('../../modules/Production/Resources/assets/js/view/machine/index_type.vue'));
Vue.component('tenant-machine-form', require('../../modules/Production/Resources/assets/js/view/machine/form.vue'));
Vue.component('tenant-machine-type-form', require('../../modules/Production/Resources/assets/js/view/machine/form_type.vue'));

Vue.component('tenant-workers-index', require('../../modules/Production/Resources/assets/js/view/workers/index.vue'));

/** produccion */

Vue.component('tenant-production-index', require('../../modules/Production/Resources/assets/js/view/production/index.vue'));
Vue.component('tenant-production-form', require('../../modules/Production/Resources/assets/js/view/production/form.vue'));

Vue.component('tenant-packaging-index', require('../../modules/Production/Resources/assets/js/view/packaging/index.vue'));
Vue.component('tenant-packaging-form', require('../../modules/Production/Resources/assets/js/view/packaging/form.vue'));

/* Restaurante */
Vue.component('tenant-restaurant-list-items', require('../../modules/Restaurant/Resources/assets/js/views/items/index.vue'));
Vue.component('tenant-restaurant-promotions-index', require('../../modules/Restaurant/Resources/assets/js/views/promotions/index.vue'));
Vue.component('tenant-restaurant-orders-index', require('../../modules/Restaurant/Resources/assets/js/views/orders/index.vue'));
Vue.component('tenant-restaurant-cash-index', require('../../modules/Restaurant/Resources/assets/js/views/cash/index.vue'));
Vue.component('tenant-restaurant-cash-filter-pos', require('../../modules/Restaurant/Resources/assets/js/views/cash/filter-pos.vue'));
Vue.component('tenant-restaurant-configuration', require('../../modules/Restaurant/Resources/assets/js/views/configuration/index.vue'));


//Pagos
Vue.component('tenant-payment-configurations-index', require('@viewsModulePayment/payment_configurations/index.vue'));
Vue.component('tenant-public-payment-links-index', require('@viewsModulePayment/payment_links/public/index.vue'));
Vue.component('tenant-payment-links-index', require('@viewsModulePayment/payment_links/index.vue'));

// MobileApp ::
Vue.component('tenant-mobile-app-configuration', require('@viewsModuleMobileApp/configuration/index.vue'));
Vue.component('tenant-mobile-app-permissions', require('@viewsModuleMobileApp/permissions/index.vue'));


// LevelAccess
Vue.component('tenant-system-activity-logs-generals-index', require('@viewsModuleLevelAccess/system_activity_logs/generals/index.vue'));
Vue.component('tenant-system-activity-logs-transactions-index', require('@viewsModuleLevelAccess/system_activity_logs/transactions/index.vue'));
Vue.component('tenant-remember-change-password', require('./views/tenant/users/partials/remember_change_password.vue'));

// Reportes en system
Vue.component('system-report-login-lockout-index', require('@viewsModuleReport/system/report_login_lockout/index.vue'));
Vue.component('system-user-not-change-password-index', require('@viewsModuleReport/system/user_not_change_password/index.vue'));


import VueClipboard from 'vue-clipboard2'
Vue.use(VueClipboard)


import moment from 'moment';

Vue.mixin({
    filters: {
        toDecimals(number, decimal = 2) {
            return Number(number).toFixed(decimal);
        },
        DecimalText: function (number, decimal = 2) {
            return isNaN(parseFloat(number)) ? number : Number(number).toFixed(decimal);
        },
        toDate(date) {
            if (date) {
                return moment(date).format('DD/MM/YYYY');
            }
            return '';
        },
        toTime(time) {
            if (time) {
                if (time.length === 5) {
                    return moment(time + ':00', 'HH:mm:ss').format('HH:mm:ss');
                }
                return moment(time, 'HH:mm:ss').format('HH:mm:ss');
            }
            return '';
        },
        pad(value, fill = '', length = 3) {
            if (value) {
                return String(value).padStart(length, fill);
            }
            return value;
        }
    },
    methods: {
        axiosError(error) {
            const response = error.response;
            const status = response.status;
            if (status === 422) {
                this.errors = response.data
            }
            if (status === 500) {
                this.$message({
                    type: 'info',
                    message: response.data.message
                  });
            }
        },
        getResponseValidations(success = true, message = null)
        {
            return {
                success: success,
                message: message
            }
        }
    }
})
const app = new Vue({
    store: store,
    el: '#main-wrapper'
});
