<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <!-- <div class="card-header bg-info">
        <h3 class="my-0">Nuevo Comprobante</h3>
    </div> -->
    <div class="tab-content" v-if="loading_form">
      <div class="invoice">
        <header class="clearfix">
          <div class="row">
            <div class="col-sm-2 text-center mt-3 mb-0">
              <logo url="/" :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''"></logo>
            </div>
            <div class="col-sm-6 text-left mt-3 mb-0">
              <address class="ib mr-2">
                <span class="font-weight-bold d-block">COTIZACIÓN</span>
                <span class="font-weight-bold d-block">COT-XXX</span>
                <span class="font-weight-bold">{{ company.name }}</span>
                <br>
                <div v-if="establishment.address != '-'">{{ establishment.address }},</div>
                {{ establishment.district.description }}, {{ establishment.province.description }},
                {{ establishment.department.description }} - {{ establishment.country.description }}
                <br>
                {{ establishment.email }} - <span
                v-if="establishment.telephone != '-'">{{ establishment.telephone }}</span>
              </address>
            </div>
          </div>
        </header>
        <form autocomplete="off" @submit.prevent="submit">
          <div class="form-body">
            <div class="row mt-1">
              <div class="col-lg-6">
                <x-input label="Cliente"
                         label-class="font-weight-bold text-info"
                         :error="errors.customer_id">
                  <template slot="label">
                    <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                  </template>
                  <el-select v-model="form.customer_id" filterable remote class="border-left rounded-left border-info"
                             popper-class="el-select-customers"
                             placeholder="Escriba el nombre o número de documento del cliente"
                             :remote-method="searchRemoteCustomers"
                             :loading="loading_search"
                             @change="changeCustomer"
                             @keyup.enter.native="keyupCustomer">
                    <el-option v-for="option in customers" :key="option.id" :value="option.id"
                               :label="option.description"></el-option>
                  </el-select>
                </x-input>
                <template v-if="customer_addresses.length > 0">
                  <x-input label="Dirección" :error="errors.customer_address_id">
                    <el-select v-model="form.customer_address_id">
                      <el-option v-for="option in customer_addresses" :key="option.id" :value="option.id"
                                 :label="option.address"></el-option>
                    </el-select>
                  </x-input>
                </template>
              </div>
              <div class="col-lg-2">
                <x-input label="Fec. Emisión" :error="errors.date_of_issue">
                  <el-date-picker v-model="form.date_of_issue"
                                  type="date"
                                  value-format="yyyy-MM-dd"
                                  format="dd/MM/yyyy"
                                  :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                </x-input>
              </div>
              <div class="col-lg-2">
                <x-input label="Tiempo de Validez" :error="errors.date_of_due">
                  <el-input v-model="form.date_of_due"></el-input>
                </x-input>
              </div>
              <div class="col-lg-2">
                <x-input label="Tiempo de Entrega" :error="errors.delivery_date">
                  <el-input v-model="form.delivery_date"></el-input>
                </x-input>
              </div>
              <div class="col-lg-4">
                <x-input label="Dirección de envío" :error="errors.shipping_address">
                  <el-input v-model="form.shipping_address"></el-input>
                </x-input>
              </div>
              <div class="col-lg-2">
                <x-input label="Término de pago" :error="errors.payment_method_type_id">
                  <el-select v-model="form.payment_method_type_id" filterable @change="changePaymentMethodType">
                    <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id"
                               :label="option.description"></el-option>
                  </el-select>
                </x-input>
              </div>
              <div class="col-lg-2">
                <x-input label="Número de cuenta" :error="errors.account_number">
                  <el-input v-model="form.account_number"></el-input>
                </x-input>
              </div>
              <div class="col-lg-2">
                <x-input label="Moneda" :error="errors.currency_type_id">
                  <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                    <el-option v-for="option in currency_types" :key="option.id" :value="option.id"
                               :label="option.description"></el-option>
                  </el-select>
                </x-input>
              </div>
              <div class="col-lg-2">
                <x-input label="Tipo de cambio"
                         tooltip-content="Tipo de cambio del día, extraído de SUNAT"
                         :error="errors.exchange_rate_sale">
                  <el-input v-model="form.exchange_rate_sale"></el-input>
                </x-input>
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-6 col-md-2">
                    <x-input label="Vendedor" :error="errors.seller_id">
                      <el-select v-model="form.seller_id" clearable>
                        <el-option v-for="sel in sellers" :key="sel.id" :value="sel.id"
                                   :label="sel.name">
                        </el-option>
                      </el-select>
                    </x-input>
                  </div>
                </div>
              </div>

              <div class="col-lg-8 mt-2">
                <label>Pagos</label>
                <table>
                  <thead>
                  <tr width="100%">
                    <th v-if="form.payments.length>0" class="pb-2">Método de pago</th>
                    <th v-if="form.payments.length>0" class="pb-2">Destino
                      <el-tooltip class="item" effect="dark" content="Aperture caja o cuentas bancarias"
                                  placement="top-start">
                        <i class="fa fa-info-circle"></i>
                      </el-tooltip>
                    </th>
                    <th v-if="form.payments.length>0" class="pb-2">Referencia</th>
                    <th v-if="form.payments.length>0" class="pb-2">Monto</th>
                    <th width="15%"><a href="#" @click.prevent="clickAddPayment"
                                       class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(row, index) in form.payments" :key="index">
                    <td>
                      <div class="form-group mb-2 mr-2">
                        <el-select v-model="row.payment_method_type_id">
                          <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id"
                                     :label="option.description"></el-option>
                        </el-select>
                      </div>
                    </td>
                    <td>
                      <div class="form-group mb-2 mr-2">
                        <el-select v-model="row.payment_destination_id" filterable>
                          <el-option v-for="option in payment_destinations" :key="option.id" :value="option.id"
                                     :label="option.description"></el-option>
                        </el-select>
                      </div>
                    </td>
                    <td>
                      <div class="form-group mb-2 mr-2">
                        <el-input v-model="row.reference"></el-input>
                      </div>
                    </td>
                    <td>
                      <div class="form-group mb-2 mr-2">
                        <el-input v-model="row.payment"></el-input>
                      </div>
                    </td>
                    <td class="series-table-actions text-center">
                      <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                              @click.prevent="clickCancel(index)">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                    <br>
                  </tr>
                  </tbody>
                </table>
              </div>

            </div>

            <div class="row mt-2">
              <div class="col-md-12">
                <el-collapse v-model="activePanel" accordion>
                  <el-collapse-item name="1">
                    <template slot="title">
                      <i class="fa fa-plus text-info"></i> &nbsp; Información Adicional<i
                      class="header-icon el-icon-information"></i>
                    </template>
                    <div class="row mt-2">
                      <div class="col-lg-6">
                        <div class="row">
                          <div class="col-6">
                            <x-input label="Contacto" :error="errors.contact">
                              <el-input v-model="form.contact"></el-input>
                            </x-input>
                          </div>
                          <div class="col-6">
                            <x-input label="Teléfono" :error="errors.phone">
                              <el-input v-model="form.phone"></el-input>
                            </x-input>
                          </div>
                          <div class="col-12">
                            <x-input label="Información referencial" :error="errors.referential_information">
                              <el-input v-model="form.referential_information"></el-input>
                            </x-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <x-input label="Observaciones" :error="errors.description">
                          <el-input type="textarea" v-model="form.description"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    maxlength="1000"
                                    show-word-limit>
                          </el-input>
                        </x-input>
                      </div>
                    </div>
                  </el-collapse-item>
                </el-collapse>
              </div>
            </div>


            <div class="row mt-3">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th class="font-weight-bold"
                          width="30%">Descripción
                      </th>
                      <th width="8%" class="text-center font-weight-bold">Unidad</th>
                      <th width="8%" class="text-center font-weight-bold">Cantidad</th>
                      <th class="text-center font-weight-bold">Valor Unitario</th>
                      <th class="text-center font-weight-bold">Precio Unitario</th>
                      <th class="text-center font-weight-bold">Desc(%)</th>
                      <th class="text-center font-weight-bold">Subtotal</th>
                      <!--<th class="text-right font-weight-bold">Cargo</th>-->
                      <th class="text-center font-weight-bold">Total</th>
                      <th width="8%"></th>
                    </tr>
                    </thead>
                    <tbody v-if="form.items.length > 0">
                    <tr v-for="(row, index) in form.items" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ setDescriptionOfItem(row) }}
                        <!--                        {{-->
                        <!--                          row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''-->
                        <!--                        }}<br/><small>{{ row.affectation_igv_type.description }}</small>-->
                      </td>
                      <td class="text-center">{{ row.unit_type_id }}</td>
                      <td class="text-center">{{ row.quantity }}</td>
                      <td class="text-center">{{ currency_type.symbol }} {{
                          getFormatUnitPriceRow(row.unit_value)
                        }}
                      </td>
                      <td class="text-center">{{ currency_type.symbol }} {{
                          getFormatUnitPriceRow(row.unit_price)
                        }}
                      </td>
                      <td class="text-center">{{ row.factor_discount * 100 }}%</td>
                      <td class="text-center">{{ currency_type.symbol }} {{ row.total_value }}</td>
                      <td class="text-center">{{ currency_type.symbol }} {{ row.total }}</td>
                      <td class="text-center">
                        <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                @click="ediItem(row.index)"><span style='font-size:10px;'>&#9998;</span></button>
                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                @click.prevent="clickRemoveItem(row.index)">x
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="9"></td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-lg-12 col-md-6 d-flex align-items-end">
                <div class="form-group">
                  <button type="button" class="btn waves-effect waves-light btn-primary" @click="clickAddItem">+ Agregar
                    Producto
                  </button>
                </div>
              </div>
              <div class="col-md-8 mt-3">
              </div>
              <div class="col-md-4">
                <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{ currency_type.symbol }}
                  {{ form.total_exportation }}</p>
                <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol }}
                  {{ form.total_free }}</p>
                <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{ currency_type.symbol }}
                  {{ form.total_unaffected }}</p>
                <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{ currency_type.symbol }}
                  {{ form.total_exonerated }}</p>
                <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }}
                  {{ form.total_taxed }}</p>
                <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }}
                  {{ form.total }}</h3>
              </div>
            </div>
          </div>
          <div class="form-actions text-right mt-4">
            <el-button @click.prevent="close()">Cancelar</el-button>
            <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit"
                       v-if="form.items.length > 0">Generar
            </el-button>
          </div>
        </form>
      </div>
    </div>

    <quotation-form-item :showDialog.sync="showDialogAddItem"
                         :currency-type-id-active="form.currency_type_id"
                         :exchange-rate-sale="form.exchange_rate_sale"
                         :typeUser="typeUser"
                         :recordItem="recordItem"
                         :configuration="config"
                         :customer-id="form.customer_id"
                         @success="addRow"></quotation-form-item>

    <person-form :showDialog.sync="showDialogNewPerson"
                 type="customers"
                 :external="true"
                 :input_person="input_person"
                 :document_type_id=form.document_type_id></person-form>

    <quotation-options :showDialog.sync="showDialogOptions"
                       :recordId="quotationNewId"
                       :typeUser="typeUser"
                       :showGenerate="false"
                       :showClose="false"></quotation-options>

    <terms-condition :showDialog.sync="showDialogTermsCondition"
                     :form="form"
                     :showClose="false"></terms-condition>
  </div>
</template>

<script>
import TermsCondition from './partials/terms_condition.vue'
import QuotationFormItem from './partials/item.vue'
import PersonForm from '../persons/form.vue'
import QuotationOptions from '../quotations/partials/options.vue'
import {functions, exchangeRate} from '../../../mixins/functions'
import {
  calculateRowItem,
  calculateRowItemOther,
  showNamePdfOfDescription,
  sumAmountDiscountsNoBaseByItem
} from '../../../helpers/functions'
import Logo from '../companies/logo.vue'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import XInput from "../../../components/XInput";

export default {
  props: [
    'typeUser',
    'saleOpportunityId',
    'configuration',
    'recordId',
  ],
  components: {XInput, QuotationFormItem, PersonForm, QuotationOptions, Logo, TermsCondition},
  mixins: [functions, exchangeRate],
  data() {
    return {
      sellers: [],
      input_person: {},
      resource: 'quotations',
      showDialogTermsCondition: false,
      showDialogAddItem: false,
      showDialogNewPerson: false,
      showDialogOptions: false,
      loading_submit: false,
      loading_form: false,
      errors: {},
      form: {},
      currency_types: [],
      discount_types: [],
      charges_types: [],
      all_customers: [],
      payment_method_types: [],
      customers: [],
      company: null,
      establishments: [],
      establishment: null,
      currency_type: {},
      quotationNewId: null,
      payment_destinations: [],
      activePanel: 0,
      customer_addresses: [],
      // configuration: {},
      loading_search: false,
      recordItem: null,
      total_discount_no_base: 0,
      store_items: []
    }
  },
  async created() {
    this.loadConfiguration()
    this.$store.commit('setConfiguration', this.configuration)
    await this.initForm()
    await this.$http.get(`/store/get_quotation_tables`)
      .then(response => {
        const data = response.data
        this.currency_types = data.currency_types
        this.establishments = data.establishments
        // this.all_customers = data.customers
        this.discount_types = data.discount_types
        this.charges_types = data.charges_types
        this.company = data.company
        this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
        this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null
        this.payment_method_types = data.payment_method_types
        this.payment_destinations = data.payment_destinations
        // this.configuration = data.configuration
        this.sellers = data.sellers;
        this.changeEstablishment()
        this.changeDateOfIssue()
        this.changeCurrencyType()
        this.allCustomers()
        this.selectDestinationSale()
      })
    this.loading_form = true

    if (this.recordId) {
      await this.$http.get(`/${this.resource}/record_other/${this.recordId}`)
        .then(response => {
          this.form = Object.assign({}, this.form, response.data.form);
          this.store_items = response.data.store_items;
        });
      await this.searchRemoteCustomer();
      this.changeCustomerAddress(this.form.customer_address_id);
      this.loadStoreItems();
    }
    this.$eventHub.$on('reloadDataPersons', (customer_id) => {
      this.reloadDataCustomers(customer_id)
    })
    this.$eventHub.$on('initInputPerson', () => {
      this.initInputPerson()
    });

    await this.createQuotationFromSO()
  },
  computed: {
    ...mapState([
      'config',
    ]),
  },
  methods: {
    ...mapActions([
      'loadConfiguration',
    ]),
    clickAddItem() {
      this.recordItem = null;
      this.showDialogAddItem = true;
    },
    ediItem(index) {
      this.recordItem = _.find(this.store_items, {'index': index});
      this.showDialogAddItem = true
    },
    changeCustomer() {
      this.changeCustomerAddress();
    },
    changeCustomerAddress(id = null) {
      this.form.customer_address_id = null;
      this.customer_addresses = [];
      let customer = _.find(this.customers, {'id': this.form.customer_id});
      if (customer) {
        this.customer_addresses = customer.addresses;
        if (this.customer_addresses.length > 0) {
          if(_.isNull(id)) {
            let address = _.find(this.customer_addresses, {'is_main': true});
            if (address) {
              this.form.customer_address_id = address.id;
            }
          } else {
            this.form.customer_address_id = id;
          }
        }
      }
    },
    changeTermsCondition() {
      if (this.form.active_terms_condition) {
        this.showDialogTermsCondition = true
      } else {
        this.form.terms_condition = null
      }
    },
    selectDestinationSale() {

      // if(this.configuration.destination_sale && this.payment_destinations.length > 0) {
      if (this.configuration.destination_sale && this.payment_destinations.length > 0 && this.form.payments.length > 0) {
        let cash = _.find(this.payment_destinations, {id: 'cash'})
        this.form.payments[0].payment_destination_id = (cash) ? cash.id : this.payment_destinations[0].id
      }

    },
    clickAddPayment() {

      this.form.payments.push({
        id: null,
        document_id: null,
        date_of_payment: moment().format('YYYY-MM-DD'),
        payment_method_type_id: '01',
        reference: null,
        payment_destination_id: this.getPaymentDestinationId(),
        payment: 0,

      });

      this.setTotalDefaultPayment()

    },
    getPaymentDestinationId() {

      if (this.configuration.destination_sale && this.payment_destinations.length > 0) {

        let cash = _.find(this.payment_destinations, {id: 'cash'})

        return (cash) ? cash.id : this.payment_destinations[0].id

      }

      return null

    },
    setTotalDefaultPayment() {

      if (this.form.payments.length > 0) {

        this.form.payments[0].payment = this.form.total
      }
    },
    clickCancel(index) {
      this.form.payments.splice(index, 1);
    },
    async createQuotationFromSO() {
      if (this.saleOpportunityId) {
        let sale_opportunity = {}
        await this.$http.get(`/sale-opportunities/record/${this.saleOpportunityId}`)
          .then(response => {
            sale_opportunity = response.data.data.sale_opportunity;
            this.reloadDataCustomers(sale_opportunity.customer_id)
          })
        await this.assignDataSaleOpportunity(sale_opportunity)
      }
    },
    assignDataSaleOpportunity(sale_opportunity) {
      this.form.establishment_id = sale_opportunity.establishment_id
      this.form.time_of_issue = moment().format("HH:mm:ss")
      this.form.customer_id = sale_opportunity.customer_id
      this.form.currency_type_id = sale_opportunity.currency_type_id
      this.form.total_exportation = sale_opportunity.total_exportation
      this.form.total_free = sale_opportunity.total_free
      this.form.total_taxed = sale_opportunity.total_taxed
      this.form.total_unaffected = sale_opportunity.total_unaffected
      this.form.total_exonerated = sale_opportunity.total_exonerated
      this.form.total_igv = sale_opportunity.total_igv
      this.form.total_taxes = sale_opportunity.total_taxes
      this.form.total_value = sale_opportunity.total_value
      this.form.total = sale_opportunity.total
      this.form.items = sale_opportunity.items
      this.form.sale_opportunity_id = sale_opportunity.id;
    },
    getFormatUnitPriceRow(unit_price) {
      return _.round(unit_price, 6)
      // return unit_price.toFixed(6)
    },
    async changePaymentMethodType(flag_submit = true) {
      // let payment_method_type = await _.find(this.payment_method_types, {'id':this.form.payment_method_type_id})
      // if(payment_method_type){

      //     if(payment_method_type.number_days){
      //         this.form.date_of_issue =  moment().add(payment_method_type.number_days,'days').format('YYYY-MM-DD');
      //         this.changeDateOfIssue()
      //     }
      // }
    },
    async searchRemoteCustomers(input) {
      this.customers = [];
      if (input.length > 0) {
        this.loading_search = true
        // let parameters = `input=${input}`
        await this.$http.post('/store/search_customers', {
          'search': input
        })
          .then(response => {
            this.customers = response.data
            /* if(this.customers.length == 0){this.allCustomers()} */
            this.input_person.number = (this.customers.length === 0) ? input : null
          })
        this.loading_search = false
      } else {
        // this.allCustomers()
        this.input_person.number = null
      }
    },
    async searchRemoteCustomer() {
      this.loading_search = true
      this.customers = [];
      await this.$http.get(`/store/search_customer/${this.form.customer_id}`)
        .then(response => {
          this.customers = response.data;
        })
      this.loading_search = false
    },
    initForm() {
      this.errors = {}
      this.form = {
        id: null,
        establishment_id: null,
        prefix: 'COT',
        series: 'COT1',
        number: '#',
        date_of_issue: moment().format('YYYY-MM-DD'),
        time_of_issue: moment().format('HH:mm:ss'),
        date_of_due: null,
        delivery_date: null,
        customer_id: null,
        customer_address_id: null,
        currency_type_id: null,
        purchase_order: null,
        exchange_rate_sale: 0,
        total_prepayment: 0,
        total_charge: 0,
        total_discount: 0,
        total_exportation: 0,
        total_free: 0,
        total_taxed: 0,
        total_unaffected: 0,
        total_exonerated: 0,
        total_igv: 0,
        total_igv_free: 0,
        total_base_isc: 0,
        total_isc: 0,
        total_base_other_taxes: 0,
        total_other_taxes: 0,
        total_taxes: 0,
        total_value: 0,
        total: 0,
        subtotal: 0,
        operation_type_id: null,
        items: [],
        charges: [],
        discounts: [],
        attributes: [],
        guides: [],
        payment_method_type_id: '10',
        additional_information: null,
        shipping_address: null,
        account_number: null,
        terms_condition: null,
        active_terms_condition: false,
        actions: {
          format_pdf: 'a4',
        },
        payments: [],
        sale_opportunity_id: null,
        contact: null,
        phone: null,
        description: '',
      }
      this.store_items = [];
      this.total_discount_no_base = 0;
      this.initInputPerson();
      // no se agrega pago por defecto para controlar flujo caja pos
      // this.clickAddPayment()

    },
    resetForm() {
      this.activePanel = 0
      this.initForm()
      this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
      this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null
      this.changeEstablishment()
      this.changeDateOfIssue()
      this.changeCurrencyType()
      this.allCustomers()
      this.customer_addresses = [];

    },
    changeEstablishment() {
      this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})

    },
    cleanCustomer() {
      this.form.customer_id = null;
    },
    changeDateOfIssue() {
      this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
        this.form.exchange_rate_sale = response
      })
    },
    allCustomers() {
      this.customers = this.all_customers
    },
    addRow(row) {

      let store_row = _.clone(row);
      /*
       * Unit Price
       */
      let unit_price_pen = parseFloat(store_row.unit_price);
      let unit_price_usd = parseFloat(store_row.unit_price);
      if (this.form.currency_type_id === 'PEN') {
        unit_price_usd = _.round(store_row.unit_price / this.form.exchange_rate_sale, 2);
      } else {
        unit_price_pen = _.round(store_row.unit_price * this.form.exchange_rate_sale, 2);
      }
      let unit_price = (this.form.currency_type_id === 'PEN') ? unit_price_pen : unit_price_usd;

      /*
       * Factor ICBPER
       */
      let factor_icbper_pen = this.config.amount_plastic_bag_taxes;
      let factor_icbper_usd = this.config.amount_plastic_bag_taxes;
      if (this.form.currency_type_id === 'PEN') {
        factor_icbper_usd = _.round(this.config.amount_plastic_bag_taxes / this.form.exchange_rate_sale, 2);
      } else {
        factor_icbper_pen = _.round(this.config.amount_plastic_bag_taxes * this.form.exchange_rate_sale, 2);
      }
      let factor_icbper = (this.form.currency_type_id === 'PEN') ? factor_icbper_pen : factor_icbper_usd;

      store_row = Object.assign({}, store_row, {
        'unit_price': unit_price,
        'unit_price_pen': unit_price_pen,
        'unit_price_usd': unit_price_usd,
        'factor_icbper': factor_icbper,
        'factor_icbper_pen': factor_icbper_pen,
        'factor_icbper_usd': factor_icbper_usd,
      });

      const indexStoreItems = _.findIndex(this.store_items, {'index': store_row.index});
      const indexFormItems = _.findIndex(this.form.items, {'index': store_row.index});

      if(indexStoreItems > -1 && indexFormItems > -1) {
        this.store_items[indexStoreItems] = store_row;
        store_row = Object.assign({}, store_row, {
          'item': _.clone(store_row),
        });
        this.form.items[indexFormItems] = calculateRowItemOther(_.clone(store_row));
      } else {
        this.store_items.push(store_row);
        store_row = Object.assign({}, store_row, {
          'item': _.clone(store_row),
        });
        this.form.items.push(calculateRowItemOther(_.clone(store_row)));
      }

      // console.log(calculateRowItemOther(_.clone(this.form)));
      // let row = calculateRowItemOther(_.clone(this.form));
      //
      //
      //
      // console.log(calculateRowItemOther(row));

      // if (this.recordItem) {
      //   this.form.items[this.recordItem.indexi] = row
      //   this.recordItem = null
      // } else {
      //   this.form.items.push(JSON.parse(JSON.stringify(row)));
      // }
      this.calculateTotal();
    },
    clickRemoveItem(index) {
      let i = _.findIndex(this.store_items, {'index': index});
      this.store_items.splice(i, 1);
      i = _.findIndex(this.form.items, {'index': index});
      this.form.items.splice(i, 1);
      this.calculateTotal()
    },
    loadStoreItems() {
      _.forEach(this.store_items, row => {
        this.form.items.push(calculateRowItemOther(_.clone(row)));
      })
    },
    changeCurrencyType() {
      this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
      let items = []
      this.form.items.forEach((row) => {
        items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
      });
      this.form.items = items
      this.calculateTotal()
    },
    calculateTotal() {

      let total_discount = 0
      let total_charge = 0
      let total_exportation = 0
      let total_taxed = 0
      let total_exonerated = 0
      let total_unaffected = 0
      let total_free = 0
      let total_igv = 0
      let total_value = 0
      let total = 0
      let total_igv_free = 0
      this.total_discount_no_base = 0

      this.form.items.forEach((row) => {
        total_discount += parseFloat(row.total_discount)
        total_charge += parseFloat(row.total_charge)

        if (row.affectation_igv_type_id === '10') {
          total_taxed += parseFloat(row.total_value)
        }
        if (row.affectation_igv_type_id === '20') {
          total_exonerated += parseFloat(row.total_value)
        }
        if (row.affectation_igv_type_id === '30') {
          total_unaffected += parseFloat(row.total_value)
        }
        if (row.affectation_igv_type_id === '40') {
          total_exportation += parseFloat(row.total_value)
        }
        if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
          total_free += parseFloat(row.total_value)
        }
        if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) > -1) {
          total_igv += parseFloat(row.total_igv)
          total += parseFloat(row.total)
        }
        total_value += parseFloat(row.total_value)

        if (['11', '12', '13', '14', '15', '16'].includes(row.affectation_igv_type_id)) {
          let unit_value = row.total_value / row.quantity
          let total_value_partial = unit_value * row.quantity
          row.total_taxes = row.total_value - total_value_partial
          row.total_igv = total_value_partial * (row.percentage_igv / 100)
          row.total_base_igv = total_value_partial
          total_value -= row.total_value
          total_igv_free += row.total_igv
        }
        //sum discount no base
        this.total_discount_no_base += sumAmountDiscountsNoBaseByItem(row)
      });

      this.form.total_igv_free = _.round(total_igv_free, 2)
      this.form.total_discount = _.round(total_discount, 2)
      this.form.total_exportation = _.round(total_exportation, 2)
      this.form.total_taxed = _.round(total_taxed, 2)
      this.form.total_exonerated = _.round(total_exonerated, 2)
      this.form.total_unaffected = _.round(total_unaffected, 2)
      this.form.total_free = _.round(total_free, 2)
      this.form.total_igv = _.round(total_igv, 2)
      this.form.total_value = _.round(total_value, 2)
      this.form.total_taxes = _.round(total_igv, 2)

      this.form.subtotal = _.round(total, 2)
      this.form.total = _.round(total - this.total_discount_no_base, 2)

      this.setTotalDefaultPayment()

    },
    validate_payments() {

      //eliminando items de pagos
      for (let index = 0; index < this.form.payments.length; index++) {
        if (parseFloat(this.form.payments[index].payment) === 0)
          this.form.payments.splice(index, 1)
      }

      let error_by_item = 0
      let acum_total = 0

      this.form.payments.forEach((item) => {
        acum_total += parseFloat(item.payment)
        if (item.payment <= 0 || item.payment == null) error_by_item++;
      })

      return {
        error_by_item: error_by_item,
        acum_total: acum_total
      }
    },
    validatePaymentDestination() {
      let error_by_item = 0
      this.form.payments.forEach((item) => {
        if (item.payment_destination_id == null) error_by_item++;
      })
      return {
        error_by_item: error_by_item,
      }
    },
    async submit() {

      let validate = await this.validate_payments()
      if (validate.acum_total > parseFloat(this.form.total) || validate.error_by_item > 0) {
        return this.$message.error('Los montos ingresados superan al monto a pagar o son incorrectos');
      }

      let validate_payment_destination = await this.validatePaymentDestination()

      if (validate_payment_destination.error_by_item > 0) {
        return this.$message.error('El destino del pago es obligatorio');
      }

      this.loading_submit = true

      await this.$http.post(`/${this.resource}`, this.form).then(response => {
        if (response.data.success) {

          this.resetForm();
          this.quotationNewId = response.data.data.id;
          this.saveCashDocument(this.quotationNewId)

          if (this.saleOpportunityId) {
            this.$message.success(`La cotización ${response.data.data.number_full} fue generada`)
            this.close()
          } else {
            this.showDialogOptions = true;
          }

        } else {
          this.$message.error(response.data.message);
        }
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data;
        } else {
          this.$message.error(error.response.data.message);
        }
      }).then(() => {
        this.loading_submit = false;
      });
    },
    close() {
      location.href = '/quotations'
    },
    reloadDataCustomers(customer_id) {
      this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
        this.customers = response.data.customers
        this.form.customer_id = customer_id
      })
    },
    setDescriptionOfItem(item) {
      return showNamePdfOfDescription(item, this.config.show_pdf_name)
    },
    async saveCashDocument(id) {
      await this.$http.post(`/cash/cash_document`, {
        quotation_id: id,
      })
        .then(response => {
          if (response.data.success) {
          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch(error => {
          console.log(error);
        })
    },
    keyupCustomer() {

      if (this.input_person.number) {

        if (!isNaN(parseInt(this.input_person.number))) {

          switch (this.input_person.number.length) {
            case 8:
              this.input_person.identity_document_type_id = '1'
              this.showDialogNewPerson = true
              break;

            case 11:
              this.input_person.identity_document_type_id = '6'
              this.showDialogNewPerson = true
              break;
            default:
              this.input_person.identity_document_type_id = '6'
              this.showDialogNewPerson = true
              break;
          }
        }
      }
    },
    initInputPerson() {
      this.input_person = {
        number: null,
        identity_document_type_id: null
      }
    },
  }
}
</script>
