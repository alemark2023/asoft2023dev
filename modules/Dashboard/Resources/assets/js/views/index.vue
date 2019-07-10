<template>
    <div>
        <header class="page-header" style="display: flex; justify-content: space-between; align-items: center">
            <div>
                <h2>Dashboard</h2>
            </div>
        </header>
        <div class="row">
            <div class="col-xl-12">
                <section class="card card-featured-left card-featured-secondary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="loadAll">
                                        <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Periodo</label>
                                <el-select v-model="form.period" @change="changePeriod">
                                    <el-option key="month" value="month" label="Por mes"></el-option>
                                    <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                                    <el-option key="date" value="date" label="Por fecha"></el-option>
                                    <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                                </el-select>
                            </div>
                            <template v-if="form.period === 'month' || form.period === 'between_months'">
                                <div class="col-md-3">
                                    <label class="control-label">Mes de</label>
                                    <el-date-picker v-model="form.month_start" type="month"
                                                    @change="changeDisabledMonths"
                                                    value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                                </div>
                            </template>
                            <template v-if="form.period === 'between_months'">
                                <div class="col-md-3">
                                    <label class="control-label">Mes al</label>
                                    <el-date-picker v-model="form.month_end" type="month"
                                                    :picker-options="pickerOptionsMonths"
                                                    @change="loadAll"
                                                    value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                                </div>
                            </template>
                            <template v-if="form.period === 'date' || form.period === 'between_dates'">
                                <div class="col-md-3">
                                    <label class="control-label">Fecha del</label>
                                    <el-date-picker v-model="form.date_start" type="date"
                                                    @change="changeDisabledDates"
                                                    value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="false"></el-date-picker>
                                </div>
                            </template>
                            <template v-if="form.period === 'between_dates'">
                                <div class="col-md-3">
                                    <label class="control-label">Fecha al</label>
                                    <el-date-picker v-model="form.date_end" type="date"
                                                    :picker-options="pickerOptionsDates"
                                                    @change="loadAll"
                                                    value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="false"></el-date-picker>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-4">
                        <section class="card card-featured-left card-featured-secondary">
                            <div class="card-body" v-if="sale_note">
                                <div class="widget-summary">
                                    <div class="widget-summary-col">
                                        <div class="row">
                                            <div class="col-md-12 m-b-10">
                                                <h2 class="card-title">Notas de venta</h2>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="summary">
                                                    <h4 class="title text-info">Total Pagado</h4>
                                                    <div class="info">
                                                        <strong class="amount text-info">S/ {{ sale_note.totals.total_payment }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="summary">
                                                    <h4 class="title text-danger">Total por Pagar</h4>
                                                    <div class="info">
                                                        <strong class="amount text-danger">S/ {{ sale_note.totals.total_to_pay }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="summary">
                                                    <h4 class="title">Total</h4>
                                                    <div class="info">
                                                        <strong class="amount">S/ {{ sale_note.totals.total }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-20">
                                            <div class="col-md-12">
                                                <x-graph type="doughnut" :all-data="sale_note.graph"></x-graph>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xl-4">
                        <section class="card card-featured-left card-featured-secondary">
                            <div class="card-body" v-if="document">
                                <div class="widget-summary">
                                    <div class="widget-summary-col">
                                        <div class="row">
                                            <div class="col-md-12 m-b-10">
                                                <h2 class="card-title">Comprobantes</h2>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="summary">
                                                    <h4 class="title text-info">Total Pagado</h4>
                                                    <div class="info">
                                                        <strong class="amount text-info">S/ {{ document.totals.total_payment }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="summary">
                                                    <h4 class="title text-danger">Total por Pagar</h4>
                                                    <div class="info">
                                                        <strong class="amount text-danger">S/ {{ document.totals.total_to_pay }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="summary">
                                                    <h4 class="title">Total</h4>
                                                    <div class="info">
                                                        <strong class="amount">S/ {{ document.totals.total }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-20">
                                            <div class="col-md-12">
                                                <x-graph type="doughnut" :all-data="document.graph"></x-graph>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xl-4">
                        <section class="card card-featured-left card-featured-secondary">
                            <div class="card-body" v-if="general">
                                <div class="widget-summary">
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <div class="row">
                                                <div class="col-md-12 m-b-10">
                                                    <h2 class="card-title">Totales</h2>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="summary">
                                                        <h4 class="title text-danger">Total notas de venta</h4>
                                                        <div class="info">
                                                            <strong class="amount text-danger">S/ {{ general.totals.total_sale_notes }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="summary">
                                                        <h4 class="title text-info">Total comprobantes</h4>
                                                        <div class="info">
                                                            <strong class="amount text-info">S/ {{ general.totals.total_documents }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="summary">
                                                        <h4 class="title">Total</h4>
                                                        <div class="info">
                                                            <strong class="amount">S/ {{ general.totals.total }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-t-20">
                                                <div class="col-md-12">
                                                    <x-graph-line :all-data="general.graph"></x-graph-line>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                     

                    <div class="col-xl-4">
                        <section class="card card-featured-left card-featured-secondary">
                            <div class="card-body" v-if="general">
                                <div class="widget-summary">
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <div class="row">
                                                <div class="col-md-12 m-b-10">
                                                    <h2 class="card-title">Compras</h2>
                                                </div> 
                                                <div class="col-lg-4">
                                                    <div class="summary">
                                                        <h4 class="title text-danger">Total percepciones</h4>
                                                        <div class="info">
                                                            <strong class="amount text-danger">S/ {{ purchase.totals.purchases_total_perception }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="summary">
                                                        <h4 class="title text-info">Total compras</h4>
                                                        <div class="info">
                                                            <strong class="amount text-info">S/ {{ purchase.totals.purchases_total }}</strong>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4">
                                                    <div class="summary">
                                                        <h4 class="title">Total</h4>
                                                        <div class="info">
                                                            <strong class="amount ">S/ {{ purchase.totals.total }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-t-20">
                                                <div class="col-md-12">
                                                    <x-graph-line :all-data="purchase.graph"></x-graph-line>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-xl-4">
                        <section class="card">
                            <div class="card-body">
                                <h2 class="card-title">Ventas por producto</h2>
                                <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Nombre</th> 
                                        <th class="text-right">Total</th> 
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-for="(row, index) in items_by_sales">
                                        <tr :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.internal_id }}</td>
                                            <td>{{ row.description }}</td>
                                            <td class="text-right">{{ row.total }}</td> 
                                           
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xl-4">
                        <section class="card">
                            <div class="card-body">
                                <h2 class="card-title">Top clientes</h2>
                                <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th class="text-right">Total</th> 
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-for="(row, index) in top_customers">
                                        <tr :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.name }}<br/>
                                                <small v-text="row.number"></small>
                                            </td>
                                            <td class="text-right">{{ row.total }}</td> 
                                           
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-xl-12">
                        <section class="card">
                            <div class="card-body">
                                <h2 class="card-title">Por cobrar</h2>
                                <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>F.Emisión</th>
                                        <th>Número</th>
                                        <th>Cliente</th>
                                        <th class="text-right">Por cobrar</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-for="(row, index) in records">
                                        <tr v-if="row.total_to_pay > 0">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.date_of_issue }}</td>
                                            <td>{{ row.number_full }}</td>
                                            <td>{{ row.customer_name }}</td>
                                            <td class="text-right text-danger">{{ row.total_to_pay }}</td>
                                            <td class="text-right">{{ row.total }}</td>
                                            <td class="text-right">
                                                <template v-if="row.type === 'document'">
                                                    <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                                            @click.prevent="clickDocumentPayment(row.id)">Pagos</button>
                                                </template>
                                                <template v-else>
                                                    <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                                            @click.prevent="clickSaleNotePayment(row.id)">Pagos</button>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </section>
                    </div>

                    
                </div>
            </div>


            
            <div class="col-xl-4">
            </div>
        </div>
        <document-payments :showDialog.sync="showDialogDocumentPayments"
                           :documentId="recordId"
                           :external="true"></document-payments>

        <sale-note-payments :showDialog.sync="showDialogSaleNotePayments"
                            :documentId="recordId"
                            :external="true"></sale-note-payments>

    </div>
</template>
<style>
    .widget-summary .summary {
        min-height: inherit;
    }
</style>
<script>

    import DocumentPayments from '../../../../../../resources/js/views/tenant/documents/partials/payments.vue'
    import SaleNotePayments from '../../../../../../resources/js/views/tenant/sale_notes/partials/payments.vue'

    export default {
        components: {DocumentPayments, SaleNotePayments},
        data() {
            return {
                resource: 'dashboard',
                establishments: [],
                document: {
                    totals: {},
                    graph: {}
                },
                sale_note: {
                    totals: {},
                    graph: {}
                },
                general: {
                    totals: {},
                    graph: {}
                },
                purchase: {
                    totals: {},
                    graph: {}
                },
                form: {},
                pickerOptionsDates: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM-DD')
                        return this.form.date_start > time
                    }
                },
                pickerOptionsMonths: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM')
                        return this.form.month_start > time
                    }
                },
                records: [],
                items_by_sales: [],
                top_customers: [],
                recordId: null,
                showDialogDocumentPayments: false,
                showDialogSaleNotePayments: false,
            }
        },
        async created() {
            this.initForm();
            await this.$http.get(`/${this.resource}/filter`)
                .then(response => {
                    this.establishments = response.data.establishments;
                    this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null;
                });
            await this.loadAll();
            await this.loadDataAditional();

            this.$eventHub.$on('reloadDataUnpaid', () => {
                this.loadUnpaid()
            })
        },
        methods: {
            initForm() {
                this.form = {
                    establishment_id: null,
                    period: 'month',
                    date_start: moment().format('YYYY-MM-DD'),
                    date_end: moment().format('YYYY-MM-DD'),
                    month_start: moment().format('YYYY-MM'),
                    month_end: moment().format('YYYY-MM'),
                }
            },
            changeDisabledDates() {
                if (this.form.date_end < this.form.date_start) {
                    this.form.date_end = this.form.date_start
                }
                this.loadAll();
            },
            changeDisabledMonths() {
                if (this.form.month_end < this.form.month_start) {
                    this.form.month_end = this.form.month_start
                }
                this.loadAll();
            },
            changePeriod() {
                if(this.form.period === 'month') {
                    this.form.month_start = moment().format('YYYY-MM');
                    this.form.month_end = moment().format('YYYY-MM');
                }
                if(this.form.period === 'between_months') {
                    this.form.month_start = moment().startOf('year').format('YYYY-MM'); //'2019-01';
                    this.form.month_end = moment().endOf('year').format('YYYY-MM');;
                }
                if(this.form.period === 'date') {
                    this.form.date_start = moment().format('YYYY-MM-DD');
                    this.form.date_end = moment().format('YYYY-MM-DD');
                }
                if(this.form.period === 'between_dates') {
                    this.form.date_start = moment().startOf('month').format('YYYY-MM-DD');
                    this.form.date_end = moment().endOf('month').format('YYYY-MM-DD');
                }
                this.loadAll();
            },
            loadAll() {
                this.loadData();
                this.loadUnpaid();
            },
            loadData() {
                this.$http.post(`/${this.resource}/data`, this.form)
                    .then(response => {
                        this.document = response.data.data.document;
                        this.sale_note = response.data.data.sale_note;
                        this.general = response.data.data.general;
                    });
            },
            loadDataAditional() {
                this.$http.get(`/${this.resource}/data_aditional`)
                    .then(response => { 
                        this.purchase = response.data.data.purchase;
                        this.items_by_sales = response.data.data.items_by_sales;
                        this.top_customers = response.data.data.top_customers;
                    });
            },
            loadUnpaid() {
                this.$http.post(`/${this.resource}/unpaid`, this.form)
                    .then(response => {
                        this.records = response.data.records;
                    });
            },
            clickDocumentPayment(recordId) {
                this.recordId = recordId;
                this.showDialogDocumentPayments = true;
            },
            clickSaleNotePayment(recordId) {
                this.recordId = recordId;
                this.showDialogSaleNotePayments = true;
            },
        }
    }
</script>
