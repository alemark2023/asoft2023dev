<template>
    <div>
        <el-dialog
            :visible="showDialog"
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :show-close="false"
            :title="titleDialog"
            width="50%"
            @open="create"
        >   
            <div class="row"> 
 
                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.series_id}" class="form-group">
                        <label class="control-label">Serie</label>
                        <el-select v-model="document.series_id">
                            <el-option
                                v-for="option in series"
                                :key="option.id"
                                :label="option.number"
                                :value="option.id"
                            ></el-option>
                        </el-select>
                        <small
                            v-if="errors.series_id"
                            class="form-control-feedback"
                            v-text="errors.series_id[0]"
                        ></small>
                    </div>
                </div>
 
  
            </div>

            <span slot="footer" class="dialog-footer">
                <el-button @click="clickClose">Cerrar</el-button>
                <el-button
                    :loading="loading_submit"
                    class="submit"
                    type="primary"
                    @click="submit"
                    >Generar</el-button> 
            </span>
        </el-dialog>

        <!-- <document-options
            :isContingency="false"
            :recordId="documentNewId"
            :showClose="true"
            :showDialog.sync="showDialogDocumentOptions"
        ></document-options>

        <sale-note-options
            :recordId="documentNewId"
            :showClose="true"
            :showDialog.sync="showDialogSaleNoteOptions"
        ></sale-note-options> -->
    </div>
</template>

<script>
// import DocumentOptions from "../../documents/partials/options.vue"
// import SaleNoteOptions from "../../sale_notes/partials/options.vue"
// import SeriesForm from "./series_form.vue"

export default {
    // components: {DocumentOptions, SaleNoteOptions, SeriesForm},

    props: [
        'showDialog',
        'orderId',
        'dataSaleNote'
    ],
    computed:{
    },
    data() {
        return {
            titleDialog: 'Generar nota de venta',
            loading: false,
            resource: "sale-notes",
            resource_documents: "sale-notes",
            errors: {},
            form: {},
            document: {},
            document_types: [],
            all_series: [],
            series: [],
            customers: [],
            generate: false,
            loading_submit: false,
            showDialogSaleNoteOptions: false,
            documentNewId: null,
        }
    },
    created() {
        this.initDocument()
    },
    methods: {   
        initDocument() {

            this.document = {
                document_type_id: '80',
                series_id: null,
                establishment_id: null,
                number: "#",
                date_of_issue: null,
                time_of_issue: null,
                customer_id: null,
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
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
            }


        }, 
        resetDocument() {
            this.initDocument()
        },
        async submit() {

            // let validate_items = await this.validateQuantityandSeries()
            // if (!validate_items.success)
            //     return this.$message.error(validate_items.message)


            // this.loading_submit = true
            this.document.prefix = "NV"
            this.resource_documents = "sale-notes"

            this.$http
                .post(`/${this.resource_documents}`, this.document)
                .then((response) => {
                    if (response.data.success) {

                        this.documentNewId = response.data.data.id
                        this.showDialogSaleNoteOptions = true
                        this.$eventHub.$emit("reloadData")
                        this.resetDocument()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        this.$message.error(error.response.data.message)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        async getTransformDataForOrder(){

            await this.$http
                .post(`/${this.resource}/transform-data-order`, this.dataSaleNote)
                .then((response) => {
                    console.log(response)
                    this.document = response.data.data
                })
        },
        assignDocument() {

            //traeria un json con la data en ingles, solo para enviar a sale notes store
            let record = this.dataSaleNote

            // console.log(this.dataSaleNote)

            // // buscar
            // // customer_id
            // // establishment_

            // this.document.date_of_issue = record.fecha_de_emision
            // this.document.time_of_issue = record.hora_de_emision
            // this.document.currency_type_id = record.codigo_tipo_moneda
             
            // this.document.exchange_rate_sale = 1

            // this.document.total_taxed = record.total_operaciones_gravadas
            // this.document.total_exonerated = record.total_operaciones_exoneradas
            // this.document.total_igv = record.total_igv
            // this.document.total_taxes = record.total_impuestos
            // this.document.total_value = record.total_valor
            // this.document.total = record.total
            // this.document.items = this.prepareItems(record.items)
            
            
        },
        prepareItems(items){
            return items.map( (row) =>{
                console.log(row)
            })
        },
        async create() {
            await this.getTransformDataForOrder()
            await this.getTables()
            await this.getRecord()
            await this.assignDocument()
        },
        async getTables(){

            await this.$http
                .get(`/${this.resource}/option/tables`)
                .then((response) => { 
                    this.all_series = response.data.series
                    this.filterSeries()
                })

        },
        async getRecord(){

            // await this.$http
            //             .get(`/${this.resource}/record2/${this.recordId}`)
            //             .then((response) => {
            //                 this.form = response.data.data
            //                 this.document.payments =
            //                     response.data.data.quotation.payments
            //                 this.document.total = this.form.quotation.total
            //                 this.document.currency_type_id = this.form.quotation.currency_type_id
            //                 this.document.payment_condition_id = this.form.quotation.payment_condition_id
            //                 if (this.document.payment_condition_id === undefined || this.document.payments.length > 0) {
            //                     this.document.payment_condition_id = "01"
            //                 }

            //                 // console.log(this.form)
            //                 // this.validateIdentityDocumentType()
            //                 this.getCustomer()
            //                 let type = this.type == "edit" ? "editada" : "registrada"
            //                 this.titleDialog =
            //                     `Cotización ${type}: ` + this.form.identifier
            //             })
        },  
        filterSeries() {
            this.document.series_id = null
            this.series = _.filter(this.all_series, {document_type_id: this.document.document_type_id})
            this.document.series_id = this.series.length > 0 ? this.series[0].id : null
        },
        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewQuotation() {
            this.clickClose()
        },
        clickClose() {
            this.$emit("update:showDialog", false)
            this.initForm()
            this.resetDocument()
        },  
        async validateQuantityandSeries() {
            let error = 0
            await this.form.quotation.items.forEach((element) => {
                if (element.item.series_enabled) {
                    const select_lots = _.filter(element.item.lots, {
                        has_sale: true,
                    }).length
                    if (select_lots != element.quantity) error++
                }
            })
            if (error > 0)
                return {
                    success: false,
                    message:
                        "Las cantidades y series seleccionadas deben ser iguales.",
                }

            return {success: true}
        },
    },
}
</script>
