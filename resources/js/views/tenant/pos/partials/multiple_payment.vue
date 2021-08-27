<template>
    <el-dialog
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
        :title="getTextPaymentMethod"
        :visible="showDialog"
        @open="create">

        <div class="form-body">
            <div class="row">
                <div v-if="!isCredit"
                     class="col-lg-12">
                    <table>
                        <thead>
                        <tr width="100%">
                            <th v-if="form_pos.payments.length>0">Método de pago</th>
                            <!-- <th v-if="payments.length>0">Destino</th> -->
                            <th v-if="form_pos.payments.length>0">Referencia</th>
                            <th v-if="form_pos.payments.length>0">Monto</th>
                            <th width="15%"><a class="text-center font-weight-bold text-info"
                                               href="#"
                                               @click.prevent="clickAddPayment()">[+ Agregar]</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in form_pos.payments"
                            :key="index">
                            <td>
                                <div class="form-group mb-2 mr-2">
                                    <el-select v-model="row.payment_method_type_id">
                                        <el-option v-for="option in cash_payment_metod"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                </div>
                            </td>
                            <!--
                            <td>
                                <div class="form-group mb-2 mr-2">
                                    <el-select v-model="row.payment_destination_id"
                                               :disabled="row.payment_destination_disabled"
                                               filterable>
                                        <el-option v-for="option in payment_destinations"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                </div>
                            </td>
                            -->
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
                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                        type="button"
                                        @click.prevent="clickCancel(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="isCredit"
                     class="col-lg-12">

                    <table v-if="form_pos.fee.length>0"
                           class="text-left"
                           width="100%">
                        <thead>
                        <tr>
                            <th v-if="form_pos.fee.length>0">Método de pago</th>
                            <th v-if="form_pos.fee.length>0"
                                class="text-left">Fecha
                            </th>
                            <th v-if="form_pos.fee.length>0"
                                class="text-left">Monto
                            </th>
                            <th width="15%">
                                <a class="text-center font-weight-bold text-info"
                                   href="#"
                                   @click.prevent="clickAddFee()">[+ Agregar]</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in form_pos.fee"
                            :key="index">
                            <td>
                                <el-select
                                    v-model="row.payment_method_type_id"
                                    @change="changePaymentMethodType(index)">
                                    <el-option
                                        v-for="option in credit_payment_metod"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                            </td>
                            <td>
                                <el-date-picker
                                    v-model="row.date"
                                    :clearable="false"
                                    format="dd/MM/yyyy"
                                    type="date"
                                    value-format="yyyy-MM-dd"></el-date-picker>
                            </td>
                            <td>
                                <el-input v-model="row.amount"></el-input>
                            </td>
                            <td class="series-table-actions text-center">
                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                        type="button"
                                        @click.prevent="clickCancelFee(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <!--  -->
                        </tr>
                        </tbody>
                    </table>


                </div>

            </div>
        </div>

        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import moment from "moment";

export default {
    props: [
        'showDialog',
        'payments',
        'total'
    ],
    data() {
        return {
            titleDialog: 'Pagos',
            loading: false,
            errors: {},
            form: {},
            company: {},
            configuration: {},
            activeName: 'first',
            // payment_method_types:[],
            payment_destinations: [],
            cards_brand: [],

        }
    },

    computed: {
        ...mapState([
            'config',
            'form_pos',
            'payment_method_types',
        ]),

        credit_payment_metod: function () {
            return _.filter(this.payment_method_types, {'is_credit': true})
        },
        cash_payment_metod: function () {
            return _.filter(this.payment_method_types, {'is_credit': false})
        },
        isCredit: function () {
            if (this.form_pos.payment_condition_id === '02') return true;
            return false
        },
        getPaymentMethod: function () {
            if (this.form_pos.payment_condition_id === '02') return _.filter(this.payment_method_types, {'is_credit': true})
            return _.filter(this.payment_method_types, {'is_credit': false})

        },
        getTextPaymentMethod: function () {
            if (this.form_pos.payment_condition_id === '01') return this.titleDialog; // +' Condicion de pago a contado';
            if (this.form_pos.payment_condition_id === '02') return this.titleDialog; // +' Condicion de pago a crédito';
        }
    },
    async created() {

        await this.$http.get(`/pos/payment_tables`)
            .then(response => {
                // this.payment_method_types = response.data.payment_method_types
                this.cards_brand = response.data.cards_brand
                this.payment_destinations = response.data.payment_destinations
                // this.clickAddPayment()
                // this.getFormPosLocalStorage()
            })
            .then(() => {
                // this.loadPos()
            })
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'loadPos',
        ]),
        getFormPosLocalStorage() {


            let form_pos = localStorage.getItem('form_pos');
            form_pos = JSON.parse(form_pos)
            if (form_pos) {

                if (form_pos.payments.length == 0) {

                    this.clickAddPayment(this.total)

                } else {
                    // console.log(form_pos.payments[0])
                    form_pos.payments[0].payment = this.total
                    this.$eventHub.$emit('localSPayments', (form_pos.payments))
                    // this.$eventHub.$emit('eventSetFormPosLocalStorage', form_pos)
                    this.$emit('add', form_pos.payments);

                }
            }

        },

        savePos() {
            // this.$store.commit('setFromPos',this.form_pos)
            this.$emit('add', this.payments);

        },
        create() {


        },
        clickAddPayment(total = 0) {
            this.form_pos.payments.push({
                id: null,
                document_id: null,
                sale_note_id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: '01',
                payment_destination_id: 'cash',
                reference: null,
                payment: total,
            });
            this.calculatePayment()
            this.savePos()

        },

        close() {
            this.savePos()
            this.$emit('update:showDialog', false)
        },
        clickCancel(index) {
            this.form_pos.payments.splice(index, 1);
            this.calculatePayment()
            this.savePos()
        },
        clickCancelFee(index) {
            this.form_pos.fee.splice(index, 1);
            this.calculateFee();
            this.savePos()
        },

        clickAddFee() {
            this.form_pos.date_of_due = moment().format('YYYY-MM-DD');
            this.form_pos.fee.push({
                id: null,
                date: moment().format('YYYY-MM-DD'),
                payment_destination_id: 'cash',
                currency_type_id: this.form_pos.currency_type_id,
                amount: 0,
            });

            this.calculateFee();
        },

        calculatePayment() {

            /*
            let payments_count = this.form_pos.payments.length;
            let total = this.form_pos.total;
            let accumulated = 0;
            let amount = _.round(total / payments_count, 2);
            _.forEach(this.form_pos.payments, row => {
                accumulated += amount;
                if (total - accumulated < 0) {
                    amount = _.round(total - accumulated + amount, 2);
                }
                row.payment = amount;
            })
            */
            this.savePos()
        },
        calculateFee() {
            let fee_count = this.form_pos.fee.length;
            let total = this.form_pos.total;
            let accumulated = 0;
            let amount = _.round(total / fee_count, 2);
            _.forEach(this.form_pos.fee, row => {
                accumulated += amount;
                if (total - accumulated < 0) {
                    amount = _.round(total - accumulated + amount, 2);
                }
                row.amount = amount;
            })
            this.savePos()
        },
        changePaymentMethodType(index) {

            let id = '01';
            if (
                this.form_pos.payments[index] !== undefined &&
                this.form_pos.payments[index].payment_method_type_id !== undefined
            ) {
                id = this.form_pos.payments[index].payment_method_type_id;
            } else if (
                this.form_pos.fee[index] !== undefined &&
                this.form_pos.fee[index].payment_method_type_id !== undefined) {
                id = this.form_pos.fee[index].payment_method_type_id;
            }
            let payment_method_type = _.find(this.payment_method_types, {'id': id});

            if (payment_method_type.number_days) {
                this.form_pos.date_of_due = moment().add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                // this.form_pos.payments = []
                this.enabled_payments = false
                this.readonly_date_of_due = true
                this.form_pos.payment_method_type_id = payment_method_type.id

                let date = moment()
                    .add(payment_method_type.number_days, 'days')
                    .format('YYYY-MM-DD')
                if (this.form_pos.fee !== undefined) {
                    for (let index = 0; index < this.form_pos.fee.length; index++) {
                        this.form_pos.fee[index].date = date;
                    }
                }

            } else if (payment_method_type.id == '09') {

                this.form_pos.payment_method_type_id = payment_method_type.id
                this.form_pos.date_of_due = this.form_pos.date_of_issue
                // this.form_pos.payments = []
                this.enabled_payments = false

            } else {

                this.form_pos.date_of_due = this.form_pos.date_of_issue
                this.readonly_date_of_due = false
                this.form_pos.payment_method_type_id = null
                this.enabled_payments = true

            }

        },
    }
}
</script>
