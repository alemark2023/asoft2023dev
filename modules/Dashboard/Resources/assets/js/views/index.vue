<template>
  <div v-if="typeUser == 'admin'">
    <header
      class="page-header"
      style="display: flex; justify-content: space-between; align-items: center"
    >
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
                    <el-option
                      v-for="option in establishments"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-md-3">
                <label class="control-label">Periodo</label>
                <el-select v-model="form.period" @change="changePeriod">
                  <el-option key="all" value="all" label="Todos"></el-option>
                  <el-option key="month" value="month" label="Por mes"></el-option>
                  <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                  <el-option key="date" value="date" label="Por fecha"></el-option>
                  <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                </el-select>
              </div>
              <template v-if="form.period === 'month' || form.period === 'between_months'">
                <div class="col-md-3">
                  <label class="control-label">Mes de</label>
                  <el-date-picker
                    v-model="form.month_start"
                    type="month"
                    @change="changeDisabledMonths"
                    value-format="yyyy-MM"
                    format="MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <template v-if="form.period === 'between_months'">
                <div class="col-md-3">
                  <label class="control-label">Mes al</label>
                  <el-date-picker
                    v-model="form.month_end"
                    type="month"
                    :picker-options="pickerOptionsMonths"
                    @change="loadAll"
                    value-format="yyyy-MM"
                    format="MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <template v-if="form.period === 'date' || form.period === 'between_dates'">
                <div class="col-md-3">
                  <label class="control-label">Fecha del</label>
                  <el-date-picker
                    v-model="form.date_start"
                    type="date"
                    @change="changeDisabledDates"
                    value-format="yyyy-MM-dd"
                    format="dd/MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <template v-if="form.period === 'between_dates'">
                <div class="col-md-3">
                  <label class="control-label">Fecha al</label>
                  <el-date-picker
                    v-model="form.date_end"
                    type="date"
                    :picker-options="pickerOptionsDates"
                    @change="loadAll"
                    value-format="yyyy-MM-dd"
                    format="dd/MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
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
          <div class="col-xl-3">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="sale_note">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10">
                        <h2 class="card-title">Notas de venta</h2>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-info">
                            Total
                            <br />Pagado
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">S/ {{ sale_note.totals.total_payment }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Total
                            <br />por Pagar
                          </h4>
                          <div class="info">
                            <strong
                              class="amount text-danger"
                            >S/ {{ sale_note.totals.total_to_pay }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title">
                            Total
                            <br />&nbsp;
                          </h4>
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
          <div class="col-xl-3">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="document">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10">
                        <h2 class="card-title">Comprobantes</h2>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-info">
                            Total
                            <br />Pagado
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">S/ {{ document.totals.total_payment }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Total
                            <br />por Pagar
                          </h4>
                          <div class="info">
                            <strong class="amount text-danger">S/ {{ document.totals.total_to_pay }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title">
                            Total
                            <br />&nbsp;
                          </h4>
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
          <div class="col-xl-6">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="general">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="summary">
                      <div class="row no-gutters">
                        <div class="col-md-12 m-b-10">
                          <h2 class="card-title">Totales</h2>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-danger">
                              Total
                              <br />notas de venta
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-danger"
                              >S/ {{ general.totals.total_sale_notes }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-info">
                              Total
                              <br />comprobantes
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-info"
                              >S/ {{ general.totals.total_documents }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title">
                              Total
                              <br />&nbsp;
                            </h4>
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
                      <div class="row no-gutters">
                        <div class="col-md-12 m-b-10">
                          <h2 class="card-title">
                            Compras
                            <el-tooltip
                              class="item"
                              effect="dark"
                              content="Aplica filtro por establecimiento"
                              placement="top-start"
                            >
                              <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                          </h2>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-danger">
                              Total
                              <br />percepciones
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-danger"
                              >S/ {{ purchase.totals.purchases_total_perception }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-info">
                              Total
                              <br />compras
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-info"
                              >S/ {{ purchase.totals.purchases_total }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title">
                              Total
                              <br />&nbsp;
                            </h4>
                            <div class="info">
                              <strong class="amount">S/ {{ purchase.totals.total }}</strong>
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
                          <td>
                            {{ row.name }}
                            <br />
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
            <dashboard-stock></dashboard-stock>
          </div>

          <div class="col-xl-12">
            <section class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <h2 class="card-title">Cuentas por cobrar</h2>
                  </div>
                  <div class="col-md-6 text-right">
                    <el-button
                      v-if="records.length > 0"
                      class="submit"
                      type="success"
                      @click.prevent="clickDownload('excel')"
                    >
                      <i class="fa fa-file-excel"></i> Exportal Excel
                    </el-button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <el-select
                      @change="changeCustomerUnpaid"
                      filterable
                      clearable
                      v-model="selected_customer"
                      placeholder="Todos"
                    >
                      <el-option
                        v-for="item in customers"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      ></el-option>
                    </el-select>
                  </div>
                  <div class="col-md-1">
                    <el-badge :value="getTotalRowsUnpaid" class="item">
                      <span size="small">Total facturas</span>
                    </el-badge>
                  </div>
                  <div class="col-md-1">
                    <el-badge :value="getTotalAmountUnpaid" class="item">
                      <span size="small">Monto total</span>
                    </el-badge>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>F.Emisión</th>
                        <th>Número</th>
                        <th>Cliente</th>

                        <th>Guías</th>

                        <th>Ver Cartera</th>

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

                          <td>
                            <template>
                              <el-popover placement="right" width="400" trigger="click">
                                <el-table :data="row.guides">
                                  <el-table-column
                                    width="120"
                                    property="date_of_issue"
                                    label="Fecha Emisión"
                                  ></el-table-column>
                                  <el-table-column width="100" property="number" label="Número"></el-table-column>
                                  <el-table-column
                                    width="100"
                                    property="date_of_shipping"
                                    label="Fecha Envío"
                                  ></el-table-column>
                                  <el-table-column fixed="right" label="Descargas" width="120">
                                    <template slot-scope="scope">
                                      <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickDownloadDispatch(scope.row.download_external_xml)"
                                      >XML</button>
                                      <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickDownloadDispatch(scope.row.download_external_pdf)"
                                      >PDF</button>
                                      <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickDownloadDispatch(scope.row.download_external_cdr)"
                                      >CDR</button>
                                    </template>
                                  </el-table-column>
                                </el-table>
                                <el-button slot="reference" icon="el-icon-view"></el-button>
                              </el-popover>
                            </template>
                          </td>

                          <td>
                            <el-popover placement="right" width="300" trigger="click">
                              <p>
                                Saldo actual:
                                <span class="custom-badge"> {{ row.total_to_pay }}</span>
                              </p>
                              <p>
                                Fecha ultimo pago:
                                <span class="custom-badge">{{ row.date_payment_last ?  row.date_payment_last : 'No registra pagos.' }}</span>
                              </p>

                              <p>
                                Dia de retraso en el pago:
                                <span class="custom-badge">{{  row.delay_payment ?  row.delay_payment : 'No tiene días atrasados.'}}</span>
                              </p>

                              <p>
                                Fecha de vencimiento:
                                <span class="custom-badge">{{  row.date_of_due ?  row.date_of_due : 'No tiene fecha de vencimiento.'}}</span>
                              </p>
                              <el-button icon="el-icon-view" slot="reference"></el-button>
                            </el-popover>
                          </td>

                          <td class="text-right text-danger">{{ row.total_to_pay }}</td>
                          <td class="text-right">{{ row.total }}</td>
                          <td class="text-right">
                            <template v-if="row.type === 'document'">
                              <button
                                type="button"
                                style="min-width: 41px"
                                class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                @click.prevent="clickDocumentPayment(row.id)"
                              >Pagos</button>
                            </template>
                            <template v-else>
                              <button
                                type="button"
                                style="min-width: 41px"
                                class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                @click.prevent="clickSaleNotePayment(row.id)"
                              >Pagos</button>
                            </template>

                            <button
                              type="button"
                              style="min-width: 41px"
                              class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                              @click.prevent="clickDocumentPayment(row.id)"
                            >Detalle</button>
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

      <div class="col-xl-4"></div>
    </div>
    <document-payments
      :showDialog.sync="showDialogDocumentPayments"
      :documentId="recordId"
      :external="true"
    ></document-payments>

    <sale-note-payments
      :showDialog.sync="showDialogSaleNotePayments"
      :documentId="recordId"
      :external="true"
    ></sale-note-payments>
  </div>
</template>
<style>
.widget-summary .summary {
  min-height: inherit;
}

.custom-badge {
  font-size: 15px;
  font-weight: bold;
}
</style>
<script>
import DocumentPayments from "../../../../../../resources/js/views/tenant/documents/partials/payments.vue";
import SaleNotePayments from "../../../../../../resources/js/views/tenant/sale_notes/partials/payments.vue";
import DashboardStock from "./partials/dashboard_stock.vue";
import queryString from "query-string";

export default {
  props: ["typeUser"],
  components: { DocumentPayments, SaleNotePayments, DashboardStock },
  data() {
    return {
      records_base: [],
      selected_customer: null,
      customers: [],
      resource: "dashboard",
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
        disabledDate: time => {
          time = moment(time).format("YYYY-MM-DD");
          return this.form.date_start > time;
        }
      },
      pickerOptionsMonths: {
        disabledDate: time => {
          time = moment(time).format("YYYY-MM");
          return this.form.month_start > time;
        }
      },
      records: [],
      items_by_sales: [],
      top_customers: [],
      recordId: null,
      showDialogDocumentPayments: false,
      showDialogSaleNotePayments: false
    };
  },
  async created() {
    this.initForm();
    await this.$http.get(`/${this.resource}/filter`).then(response => {
      this.establishments = response.data.establishments;
      this.form.establishment_id =
        this.establishments.length > 0 ? this.establishments[0].id : null;
    });
    await this.loadAll();

    this.$eventHub.$on("reloadDataUnpaid", () => {
      this.loadAll();
    });
  },
  computed: {
    getTotalRowsUnpaid() {
      const self = this;

      if (self.selected_customer) {
        return _.filter(self.records, function(item) {
          return (
            item.total_to_pay > 0 && item.customer_id == self.selected_customer
          );
        }).length;
      } else {
        return _.filter(this.records, function(item) {
          return item.total_to_pay > 0;
        }).length;
      }
    },
    getTotalAmountUnpaid() {
      const self = this;
      let source = [];
      if (self.selected_customer) {
        source = _.filter(self.records, function(item) {
          return (
            item.total_to_pay > 0 && item.customer_id == self.selected_customer
          );
        });
      } else {
        source = _.filter(this.records, function(item) {
          return item.total_to_pay > 0;
        });
      }

      return _.sumBy(source, function(item) {
        return parseFloat(item.total);
      }).toFixed(2);
    }
  },
  methods: {
    clickDownloadDispatch(download) {
      window.open(download, "_blank");
    },
    changeCustomerUnpaid() {
      if (this.selected_customer) {
        this.records = _.filter(this.records_base, {
          customer_id: this.selected_customer
        });
      } else {
        this.records = this.records_base;
      }
    },
    clickDownload(type) {
      let query = queryString.stringify({
        ...this.form
      });
      window.open(`/reports/no_paid/${type}/?${query}`, "_blank");
    },
    initForm() {
      this.form = {
        establishment_id: null,
        period: "all",
        date_start: moment().format("YYYY-MM-DD"),
        date_end: moment().format("YYYY-MM-DD"),
        month_start: moment().format("YYYY-MM"),
        month_end: moment().format("YYYY-MM")
      };
    },
    changeDisabledDates() {
      if (this.form.date_end < this.form.date_start) {
        this.form.date_end = this.form.date_start;
      }
      this.loadAll();
    },
    changeDisabledMonths() {
      if (this.form.month_end < this.form.month_start) {
        this.form.month_end = this.form.month_start;
      }
      this.loadAll();
    },
    changePeriod() {
      if (this.form.period === "month") {
        this.form.month_start = moment().format("YYYY-MM");
        this.form.month_end = moment().format("YYYY-MM");
      }
      if (this.form.period === "between_months") {
        this.form.month_start = moment()
          .startOf("year")
          .format("YYYY-MM"); //'2019-01';
        this.form.month_end = moment()
          .endOf("year")
          .format("YYYY-MM");
      }
      if (this.form.period === "date") {
        this.form.date_start = moment().format("YYYY-MM-DD");
        this.form.date_end = moment().format("YYYY-MM-DD");
      }
      if (this.form.period === "between_dates") {
        this.form.date_start = moment()
          .startOf("month")
          .format("YYYY-MM-DD");
        this.form.date_end = moment()
          .endOf("month")
          .format("YYYY-MM-DD");
      }
      this.loadAll();
    },
    loadAll() {
      this.loadData();
      this.loadUnpaid();
      this.loadDataAditional();
      //this.loadCustomer();
    },
    loadData() {
      this.$http.post(`/${this.resource}/data`, this.form).then(response => {
        this.document = response.data.data.document;
        this.sale_note = response.data.data.sale_note;
        this.general = response.data.data.general;
        this.customers = response.data.data.customers;
      });
    },
    loadDataAditional() {
      this.$http
        .post(`/${this.resource}/data_aditional`, this.form)
        .then(response => {
          this.purchase = response.data.data.purchase;
          this.items_by_sales = response.data.data.items_by_sales;
          this.top_customers = response.data.data.top_customers;
        });
    },
    loadUnpaid() {
      this.$http.post(`/${this.resource}/unpaid`, this.form).then(response => {
        this.records = response.data.records;
        this.records_base = response.data.records;
      });
    },
    clickDocumentPayment(recordId) {
      this.recordId = recordId;
      this.showDialogDocumentPayments = true;
    },
    clickSaleNotePayment(recordId) {
      this.recordId = recordId;
      this.showDialogSaleNotePayments = true;
    }
  }
};
</script>
