
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import ElementUI from 'element-ui'
import Axios from 'axios'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
locale.use(lang)

//Vue.use(ElementUI)
Vue.use(ElementUI, {size: 'small'})
Vue.prototype.$eventHub = new Vue()
Vue.prototype.$http = Axios

// import VueCharts from 'vue-charts'
// Vue.use(VueCharts);
// import { TableComponent, TableColumn } from 'vue-table-component';
//
// Vue.component('table-component', TableComponent);
// Vue.component('table-column', TableColumn);
Vue.component('tenant-dashboard-index', require('../../modules/Dashboard/Resources/assets/js/views/index.vue').default);

Vue.component('x-graph', require('./components/graph/src/Graph.vue').default);
Vue.component('x-graph-line', require('./components/graph/src/GraphLine.vue').default);

Vue.component('tenant-companies-form', require('./views/tenant/companies/form.vue').default);
Vue.component('tenant-companies-logo', require('./views/tenant/companies/logo.vue').default);
Vue.component('tenant-certificates-index', require('./views/tenant/certificates/index.vue').default);
Vue.component('tenant-certificates-form', require('./views/tenant/certificates/form.vue').default);
Vue.component('tenant-configurations-form', require('./views/tenant/configurations/form.vue').default);
Vue.component('tenant-configurations-visual', require('./views/tenant/configurations/visual.vue').default);
Vue.component('tenant-configurations-pdf', require('./views/tenant/configurations/pdf_templates.vue').default);
// Vue.component('tenant-establishments-form', require('./views/tenant/establishments/form.vue').default);
// Vue.component('tenant-series-form', require('./views/tenant/series/form.vue').default);
Vue.component('tenant-bank_accounts-index', require('./views/tenant/bank_accounts/index.vue').default);
Vue.component('tenant-items-index', require('./views/tenant/items/index.vue').default);
Vue.component('tenant-persons-index', require('./views/tenant/persons/index.vue').default);
// Vue.component('tenant-customers-index', require('./views/tenant/customers/index.vue').default);
// Vue.component('tenant-suppliers-index', require('./views/tenant/suppliers/index.vue').default);
Vue.component('tenant-users-form', require('./views/tenant/users/form.vue').default);
Vue.component('tenant-documents-index', require('./views/tenant/documents/index.vue').default);
Vue.component('tenant-documents-invoice', require('./views/tenant/documents/invoice.vue').default);
Vue.component('tenant-documents-invoicetensu', require('./views/tenant/documents/invoicetensu.vue').default);
Vue.component('tenant-documents-note', require('./views/tenant/documents/note.vue').default);
Vue.component('tenant-summaries-index', require('./views/tenant/summaries/index.vue').default);
Vue.component('tenant-voided-index', require('./views/tenant/voided/index.vue').default);
Vue.component('tenant-search-index', require('./views/tenant/search/index.vue').default);
Vue.component('tenant-options-form', require('./views/tenant/options/form.vue').default);
Vue.component('tenant-unit_types-index', require('./views/tenant/unit_types/index.vue').default);
Vue.component('tenant-detraction_types-index', require('./views/tenant/detraction_types/index.vue').default);
Vue.component('tenant-users-index', require('./views/tenant/users/index.vue').default);
Vue.component('tenant-establishments-index', require('./views/tenant/establishments/index.vue').default);
Vue.component('tenant-charge_discounts-index', require('./views/tenant/charge_discounts/index.vue').default);
Vue.component('tenant-banks-index', require('./views/tenant/banks/index.vue').default);
Vue.component('tenant-exchange_rates-index', require('./views/tenant/exchange_rates/index.vue').default);
Vue.component('tenant-currency-types-index', require('./views/tenant/currency_types/index.vue').default);
Vue.component('tenant-retentions-index', require('./views/tenant/retentions/index.vue').default);
Vue.component('tenant-retentions-form', require('./views/tenant/retentions/form.vue').default);
Vue.component('tenant-perceptions-index', require('./views/tenant/perceptions/index.vue').default);
Vue.component('tenant-perceptions-form', require('./views/tenant/perceptions/form.vue').default);
Vue.component('tenant-dispatches-index', require('./views/tenant/dispatches/index.vue').default);
Vue.component('tenant-dispatches-form', require('./views/tenant/dispatches/form.vue').default);
Vue.component('tenant-dispatches-create', require('./views/tenant/dispatches/create.vue').default);
Vue.component('tenant-purchases-index', require('./views/tenant/purchases/index.vue').default);
Vue.component('tenant-purchases-form', require('./views/tenant/purchases/form.vue').default);
Vue.component('tenant-purchases-edit', require('./views/tenant/purchases/form_edit.vue').default);

Vue.component('tenant-purchases-items', require('./views/tenant/dispatches/items.vue').default);
Vue.component('tenant-attribute_types-index', require('./views/tenant/attribute_types/index.vue').default);
Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue').default);
Vue.component('tenant-warehouses', require('./views/tenant/components/warehouses.vue').default);
Vue.component('tenant-calendar-quotation', require('./views/tenant/components/calendarquotations.vue').default);

//Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue').default);
Vue.component('tenant-product', require('./views/tenant/components/products.vue').default);


Vue.component('tenant-tasks-lists', require('./views/tenant/tasks/lists.vue').default);
Vue.component('tenant-tasks-form', require('./views/tenant/tasks/form.vue').default);
Vue.component('tenant-reports-consistency-documents-lists', require('./views/tenant/reports/consistency-documents/lists.vue').default);
Vue.component('tenant-contingencies-index', require('./views/tenant/contingencies/index.vue').default);

Vue.component('tenant-quotations-index', require('./views/tenant/quotations/index.vue').default);
Vue.component('tenant-quotations-form', require('./views/tenant/quotations/form.vue').default);
Vue.component('tenant-quotations-edit', require('./views/tenant/quotations/form_edit.vue').default);


Vue.component('tenant-sale-notes-index', require('./views/tenant/sale_notes/index.vue').default);
Vue.component('tenant-sale-notes-form', require('./views/tenant/sale_notes/form.vue').default);
Vue.component('tenant-pos-index', require('./views/tenant/pos/index.vue').default);
Vue.component('cash-index', require('./views/tenant/cash/index.vue').default);
Vue.component('tenant-card-brands-index', require('./views/tenant/card_brands/index.vue').default);

Vue.component('tenant-payment-method-index', require('./views/tenant/payment_method/index.vue').default);
Vue.component('tenant-payment-method-index', require('./views/tenant/payment_method/index.vue').default);



// Modules
Vue.component('inventory-index', require('../../modules/Inventory/Resources/assets/js/inventory/index.vue').default);
Vue.component('inventory-transfers-index', require('../../modules/Inventory/Resources/assets/js/transfers/index.vue').default);
Vue.component('warehouses-index', require('../../modules/Inventory/Resources/assets/js/warehouses/index.vue').default);
Vue.component('tenant-report-kardex-index', require('../../modules/Inventory/Resources/assets/js/kardex/index.vue').default);
Vue.component('tenant-inventories-form', require('../../modules/Inventory/Resources/assets/js/config/form.vue').default);
Vue.component('tenant-expenses-index', require('../../modules/Expense/Resources/assets/js/views/expenses/index.vue').default);
Vue.component('tenant-expenses-form', require('../../modules/Expense/Resources/assets/js/views/expenses/form.vue').default);
Vue.component('tenant-account-export', require('../../modules/Account/Resources/assets/js/views/account/export.vue').default);
Vue.component('tenant-account-summary-report', require('../../modules/Account/Resources/assets/js/views/summary_report/index.vue').default);
Vue.component('tenant-account-format', require('../../modules/Account/Resources/assets/js/views/account/format.vue').default);
Vue.component('tenant-company-accounts', require('../../modules/Account/Resources/assets/js/views/company_accounts/form.vue').default);

Vue.component('tenant-documents-not-sent', require('../../modules/Document/Resources/assets/js/views/documents/not_sent.vue').default);
Vue.component('tenant-report-purchases-index', require('../../modules/Report/Resources/assets/js/views/purchases/index.vue').default);
Vue.component('tenant-report-documents-index', require('../../modules/Report/Resources/assets/js/views/documents/index.vue').default);
Vue.component('tenant-report-customers-index', require('../../modules/Report/Resources/assets/js/views/customers/index.vue').default);
Vue.component('tenant-report-items-index', require('../../modules/Report/Resources/assets/js/views/items/index.vue').default);
Vue.component('tenant-report-sale_notes-index', require('../../modules/Report/Resources/assets/js/views/sale_notes/index.vue').default);
Vue.component('tenant-report-quotations-index', require('../../modules/Report/Resources/assets/js/views/quotations/index.vue').default);
Vue.component('tenant-report-cash-index', require('../../modules/Report/Resources/assets/js/views/cash/index.vue').default);
Vue.component('tenant-index-configuration', require('../../modules/BusinessTurn/Resources/assets/js/views/configurations/index.vue').default);
Vue.component('tenant-report-document_hotels-index', require('../../modules/Report/Resources/assets/js/views/document_hotels/index.vue').default);
Vue.component('tenant-report-commercial_analysis-index', require('../../modules/Report/Resources/assets/js/views/commercial_analysis/index.vue').default);
Vue.component('tenant-offline-configurations-index', require('../../modules/Offline/Resources/assets/js/views/offline_configurations/index.vue').default);
Vue.component('tenant-series-configurations-index', require('../../modules/Document/Resources/assets/js/views/series_configurations/index.vue').default);
Vue.component('tenant-validate-documents-index', require('../../modules/Document/Resources/assets/js/views/validate_documents/index.vue').default);
Vue.component('tenant-report-document-detractions-index', require('../../modules/Report/Resources/assets/js/views/document-detractions/index.vue').default);
Vue.component('tenant-report-commissions-index', require('../../modules/Report/Resources/assets/js/views/commissions/index.vue').default);
Vue.component('tenant-report-order-notes-consolidated-index', require('../../modules/Report/Resources/assets/js/views/order_notes_consolidated/index.vue').default);
Vue.component('tenant-report-general-items-index', require('../../modules/Report/Resources/assets/js/views/general_items/index.vue').default);
Vue.component('tenant-report-order-notes-general-index', require('../../modules/Report/Resources/assets/js/views/order_notes_general/index.vue').default);
Vue.component('tenant-report-sales-consolidated-index', require('../../modules/Report/Resources/assets/js/views/sales_consolidated/index.vue').default);


Vue.component('tenant-categories-index', require('../../modules/Item/Resources/assets/js/views/categories/index.vue').default);
Vue.component('tenant-brands-index', require('../../modules/Item/Resources/assets/js/views/brands/index.vue').default);
Vue.component('tenant-incentives-index', require('../../modules/Item/Resources/assets/js/views/incentives/index.vue').default);

Vue.component('tenant-ecommerce-configuration-info', require('../../modules/Ecommerce/Resources/assets/js/views/configuration/index.vue').default);
Vue.component('tenant-ecommerce-configuration-culqi', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_culqi/index.vue').default);
Vue.component('tenant-ecommerce-configuration-paypal', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_paypal/index.vue').default);
Vue.component('tenant-ecommerce-configuration-logo', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_logo/index.vue').default);
Vue.component('tenant-ecommerce-configuration-social', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_social/index.vue').default);
Vue.component('tenant-ecommerce-configuration-tag', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_tags/index.vue').default);

Vue.component('tenant-purchase-quotations-index', require('../../modules/Purchase/Resources/assets/js/views/purchase-quotations/index.vue').default);
Vue.component('tenant-purchase-quotations-form', require('../../modules/Purchase/Resources/assets/js/views/purchase-quotations/form.vue').default);

Vue.component('tenant-purchase-orders-index', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/index.vue').default);
Vue.component('tenant-purchase-orders-form', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/form.vue').default);
Vue.component('tenant-purchase-orders-generate', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/generate.vue').default);

Vue.component('moves-index', require('../../modules/Inventory/Resources/assets/js/moves/index.vue').default);
Vue.component('inventory-form-masive', require('../../modules/Inventory/Resources/assets/js/transfers/form_masive.vue').default);

Vue.component('tenant-report-kardex-master', require('../../modules/Inventory/Resources/assets/js/kardex_master/index.vue').default);
Vue.component('tenant-report-kardex-lots', require('../../modules/Inventory/Resources/assets/js/kardex/lots.vue').default);
Vue.component('tenant-report-kardex-series', require('../../modules/Inventory/Resources/assets/js/kardex/series.vue').default);

Vue.component('tenant-order-notes-index', require('../../modules/Order/Resources/assets/js/views/order_notes/index.vue').default);
Vue.component('tenant-order-notes-form', require('../../modules/Order/Resources/assets/js/views/order_notes/form.vue').default);
Vue.component('tenant-order-notes-edit', require('../../modules/Order/Resources/assets/js/views/order_notes/form_edit.vue').default);
Vue.component('tenant-report-valued-kardex', require('../../modules/Inventory/Resources/assets/js/valued_kardex/index.vue').default);

//Finance
Vue.component('tenant-finance-global-payments-index', require('../../modules/Finance/Resources/assets/js/views/global_payments/index.vue').default);
Vue.component('tenant-finance-balance-index', require('../../modules/Finance/Resources/assets/js/views/balance/index.vue').default);
Vue.component('tenant-finance-payment-method-types-index', require('../../modules/Finance/Resources/assets/js/views/payment_method_types/index.vue').default);
Vue.component('tenant-finance-unpaid-index', require('@viewsModuleFinance/unpaid/index.vue').default);
Vue.component('tenant-finance-to-pay-index', require('@viewsModuleFinance/to_pay/index.vue').default);
Vue.component('tenant-finance-income-index', require('@viewsModuleFinance/income/index.vue').default);
Vue.component('tenant-finance-income-form', require('@viewsModuleFinance/income/form.vue').default);
Vue.component('tenant-income-types-index', require('@viewsModuleFinance/income_types/index.vue').default);
Vue.component('tenant-income-reasons-index', require('@viewsModuleFinance/income_reasons/index.vue').default);


//Sale
Vue.component('tenant-sale-opportunities-index', require('@viewsModuleSale/sale_opportunities/index.vue').default);
Vue.component('tenant-sale-opportunities-form', require('@viewsModuleSale/sale_opportunities/form.vue').default);
Vue.component('tenant-payment-method-types-index', require('@viewsModuleSale/payment_method_types/index.vue').default);
Vue.component('tenant-contracts-index', require('@viewsModuleSale/contracts/index.vue').default);
Vue.component('tenant-contracts-form', require('@viewsModuleSale/contracts/form.vue').default);
Vue.component('tenant-production-orders-index', require('@viewsModuleSale/production_orders/index.vue').default);

//Purchase

Vue.component('tenant-fixed-asset-items-index', require('@viewsModulePurchase/fixed_asset_items/index.vue').default);
Vue.component('tenant-fixed-asset-purchases-index', require('@viewsModulePurchase/fixed_asset_purchases/index.vue').default);
Vue.component('tenant-fixed-asset-purchases-form', require('@viewsModulePurchase/fixed_asset_purchases/form.vue').default);

//Expense

Vue.component('tenant-expense-types-index', require('@viewsModuleExpense/expense_types/index.vue').default);
Vue.component('tenant-expense-reasons-index', require('@viewsModuleExpense/expense_reasons/index.vue').default);
Vue.component('tenant-expense-method-types-index', require('@viewsModuleExpense/expense_method_types/index.vue').default);


// System
Vue.component('system-clients-index', require('./views/system/clients/index.vue').default);
Vue.component('system-clients-form', require('./views/system/clients/form.vue').default);
Vue.component('system-users-form', require('./views/system/users/form.vue').default);

Vue.component('system-certificate-index', require('./views/system/certificate/index.vue').default);
Vue.component('system-companies-form', require('./views/system/companies/form.vue').default);



Vue.component('system-plans-index', require('./views/system/plans/index.vue').default);
Vue.component('system-plans-form', require('./views/system/plans/form.vue').default);

Vue.component('x-input-service', require('./components/InputService.vue').default);

Vue.component('tenant-items-ecommerce-index', require('./views/tenant/items_ecommerce/index.vue').default);
Vue.component('tenant-ecommerce-cart', require('./views/tenant/ecommerce/cart_dropdown.vue').default);
Vue.component('tenant-tags-index', require('./views/tenant/tags/index.vue').default);
Vue.component('tenant-promotions-index', require('./views/tenant/promotions/index.vue').default);

Vue.component('tenant-item-sets-index', require('./views/tenant/item_sets/index.vue').default);
Vue.component('tenant-person-types-index', require('./views/tenant/person_types/index.vue').default);

Vue.component('tenant-orders-index', require('./views/tenant/orders/index.vue').default);

//Cuenta
Vue.component('tenant-account-payment-index', require('./views/tenant/account/payment_index.vue').default);
Vue.component('tenant-account-configuration-index', require('./views/tenant/account/configuration.vue').default);

//auto update
Vue.component('system-update', require('./views/system/update/index.vue').default);

const app = new Vue({
    el: '#main-wrapper'
});
