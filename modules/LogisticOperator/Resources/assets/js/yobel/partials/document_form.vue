<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="title"
        :visible="showDialog"
        top="7vh"
        @close="close"
        @open="create"
    >

        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div v-loading="loader"
                     class="row">
                    <template v-if="form">
                        <div v-if="form.items"
                             class="col-md-12">
                            <div class="table-responsive"
                                 style="margin:3px">
                                <h5 class="separator-title">
                                    Productos
                                </h5>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Cantidad</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items"
                                        :key="index">

                                        <td class="text-center">{{ row.descripcion }}</td>
                                        <td class="text-center">{{ row.cantidad }}</td>
                                        <td class="series-table-actions text-right">
                                            <button :disabled="!row.lots || row.lots.length == 0"
                                                    class="btn waves-effect waves-light btn-md btn-success"
                                                    type="button"
                                                    @click.prevent="openDialogLots(row.lots, row.cantidad)">
                                                <i class="el-icon-check"></i> Series
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="closeDialog()">Cancelar</el-button>
                <el-button class="add"
                           native-type="submit"
                           type="primary">Generar
                </el-button>
            </div>
        </form>

        <lots :lots="lots"
              :showDialog.sync="showDialogLots"
              :stock="stock"></lots>


    </el-dialog>
</template>

<script>

import Lots from "./lots.vue";

export default {
    components: {Lots},
    props: [
        'resource',
        'order_id',
        'user'
    ],
    data() {
        return {
            form: null,
            // resource: 'orders',
            showDialog: false,
            lots: [],
            stock: 0,
            loader: false,
            title: null,
            showDialogLots: false,
            all_series: [],
            establishments: [],
            establishment_id: [],
            document_types: []
        };
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then((response => {
                this.all_series = response.data.series
                this.establishments = response.data.establishments
                this.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null;
                this.document_types = response.data.document_types
            }))
    },
    computed: {
        headerConfig() {
            let token = this.user.api_token
            let httpConfig = {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            }
            return httpConfig
        }
    },
    methods: {
        async submit() {

            let validate_items = await this.validateQuantityandSeries()
            if (!validate_items.success)
                return this.$message.error(validate_items.message);


            const {codigo_tipo_documento} = this.form
            const series_id = await this.filterSeries(codigo_tipo_documento)

            if (!series_id)
                return this.$message.error("Serie de documento no disponibles")

            this.form.serie_documento = series_id


            try {

                this.loader = true

                const {data} = await this.$http.post(`/api/documents`, this.form, this.headerConfig)

                const {datos_del_cliente_o_receptor, totales} = this.form

                const payloadFinally = {
                    document_external_id: data.data.external_id,
                    number_document: data.data.number,
                    orderId: this.order_id,
                    product: 'Compras Ecommerce Facturador Pro',
                    precio_culqi: Number(totales.total_venta),
                    identity_document_type_id: datos_del_cliente_o_receptor.codigo_tipo_documento_identidad,
                    number: datos_del_cliente_o_receptor.numero_documento
                }

                await this.finallyProcess(payloadFinally)
                await this.saveUpdateStatus()

                this.$message.success('Transaccion finalizada correctamente')

                this.closeDialog()

            } catch (error) {
                this.$message.error(error.response.data.message)
            } finally {
                this.loader = false

            }
        },
        async finallyProcess(form) {
            await this.$http.post(`/ecommerce/transaction_finally`, form)

        },
        saveUpdateStatus() {

            this.$http.post(`/statusOrder/update`, {record: {id: this.order_id, status_order_id: 2}})

        },
        async sendPreview(purchase) {
            this.showDialog = true
            this.loader = true

            this.form = purchase
            const type = await _.find(this.document_types, {'id': this.form.codigo_tipo_documento});
            this.title = `Generar ${type.description}`
            await this.setLotsItem()

            this.loader = false

        },
        async create() {

        },
        async setLotsItem() {
            for (const element of this.form.items) {

                const item = await this.getItem(element.codigo_interno)
                element['lots'] = item.lots
                element['series_enabled'] = item.series_enabled
            }

        },
        close() {
            this.$eventHub.$emit('reloadData')
            this.form = null
        },
        async getItem(id) {
            const record = await this.$http.get(`${this.resource}/tables/item/${id}`)
            return record.data
        },
        openDialog() {
            this.showDialog = true
        },
        closeDialog() {
            this.showDialog = false
        },
        openDialogLots(lt, stk) {
            this.lots = lt
            this.stock = stk
            this.showDialogLots = true
        },
        async validateQuantityandSeries() {


            let error = 0

            await this.form.items.forEach(element => {

                if (element.series_enabled) {
                    const select_lots = _.filter(element.lots, {has_sale: true}).length

                    if (select_lots != element.cantidad)
                        error++
                }

            });

            if (error > 0)
                return {success: false, message: 'Las cantidades y series seleccionadas deben ser iguales.'}


            return {success: true}
        },
        filterSeries(document_type_id) {
            let series_id = null
            let series = _.filter(this.all_series, {
                'establishment_id': this.establishment_id,
                'document_type_id': document_type_id,
                'contingency': false
            });

            series_id = (series.length > 0) ? series[0].number : null
            return series_id
        },
    }
};
</script>
