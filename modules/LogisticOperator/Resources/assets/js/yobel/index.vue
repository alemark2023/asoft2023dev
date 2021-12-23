<template>
    <div v-loading="loading_submit">
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Pedidos</span>
                </li>
            </ol>


            <div class="right-wrapper pull-right">
                <div class="btn-group flex-wrap">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown" aria-expanded="false"><i class="fa fa-upload"></i> Importar <span
                        class="caret"></span></button>
                    <div class="dropdown-menu" role="menu" x-placement="bottom-start"
                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 42px, 0px);">
                        <a class="dropdown-item text-1" href="#" @click.prevent="clickImportSet()">
                            Pedidos de Yobel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Pedidos  -Operador logistico YOBEL-</h3>
<!--                <h3 class="my-0">Listado de Pedidos Tienda Virtual</h3>-->
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading"
                        width="100%">
                        <th>#</th>
                        <th>Codigo de Pedido</th>
                        <th>Cliente</th>
                        <th class="text-center">Detalle Productos</th>
                        <th>Total</th>
                        <th>Fecha Emision</th>
                        <th>Medio Pago</th>
                        <th>Estatus del Pedido</th>
                        <th class="text-center">Documento</th>
                        <th class="text-center">Opciones</th>
                    </tr>
                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.order_id }}</td>
                        <td>{{ row.customer }}</td>
                        <td class="text-center">
                            <template>
                                <el-popover placement="right"
                                            trigger="click"
                                            width="540">
                                    <el-table :data="row.items"
                                              style="width: 100%">

                                        <el-table-column label="Nombre"
                                                         property="description"
                                                         width="150"></el-table-column>
                                        <el-table-column label="Cant."
                                                         property="cantidad"
                                                         width="90"></el-table-column>
                                        <el-table-column label="Precio"
                                                         width="90">
                                            <template slot-scope="scope">
                                                <span>{{
                                                        (scope.row.currency_type_id === 'USD') ? '$' : 'S/'
                                                      }} {{ Number(scope.row.sale_unit_price).toFixed(2) }}</span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="T/C"
                                                         property="exchange_rate_sale"
                                                         width="90"></el-table-column>
                                        <el-table-column label="Subtotal"
                                                         width="90">
                                            <template slot-scope="scope">
                                                <span>S/ {{ subtotal(scope.row) }}</span>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <table class="el-table--small el-table--fit el-table">
                                        <thead class="has-gutter">
                                        <th class="text-center"
                                            colspan="2">Contacto
                                        </th>
                                        </thead>
                                        <tbody>
                                        <tr class="el-table tr">
                                            <td class="el-table--small td">TELÉFONO: {{ row.customer_telefono }}</td>
                                        </tr>
                                        <tr class="el-table tr">
                                            <td class="el-table--small td">DIRECCIÓN: {{ row.customer_direccion }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <el-button slot="reference"
                                               icon="el-icon-zoom-in"></el-button>
                                </el-popover>
                            </template>
                        </td>
                        <td>S/ {{ row.total }}</td>
                        <td>{{ row.created_at }}</td>
                        <td>{{ row.reference_payment }}</td>
                        <td>
                            <el-select
                                v-model="row.status_order_id"
                                :value="row.status_order_id"
                                placeholder="Estatus Pedido"
                                @change="updateStatus(row)"
                            >
                                <el-option
                                    v-for="item in options"
                                    :key="item.id"
                                    :label="item.description"
                                    :value="item.id"
                                ></el-option>
                            </el-select>
                        </td>
                        <td class="text-center">
                            <template v-if="row.document_type_id == '80'">
                                {{ row.sale_note_number_full }}
                            </template>
                            <template v-else>
                                {{ row.number_document }}
                            </template>
                        </td>
                        <td class="text-center">
                            <template v-if="row.document_type_id == '80'">
                                <el-button v-if="row.sale_note_id"
                                           class="submit"
                                           icon="el-icon-tickets"
                                           type="success"
                                           @click.prevent="clickOptions(row.sale_note_id)"></el-button>
                            </template>
                            <template v-else>
                                <el-button v-if="row.document_external_id"
                                           class="submit"
                                           icon="el-icon-tickets"
                                           type="success"
                                           @click.prevent="clickDownload(row.document_external_id)"></el-button>
                            </template>

                            <el-button
                                       class="submit"
                                       type="success"
                                       @click.prevent="openWindow(row.test_embarque)"> Embarque</el-button>
                            <el-button
                                       class="submit"
                                       type="success"
                                       @click.prevent="openWindow(row.test_cliente)"> cliente</el-button>
                            <el-button
                                       class="submit"
                                       type="success"
                                       @click.prevent="openWindow(row.test_pedido)"> Pedido</el-button>





                        </td>
                    </tr>
                </data-table>

            </div>
        </div>

        <el-dialog
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :show-close="false"
            :visible="showDialog"
            append-to-body
            title="Stock en almacén"
            width="40%"
        >
            <div class="form-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 table-responsive">
                        <table class="table"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Almacén</th>
                            </tr>
                            </thead>
                            <tbody
                                v-for="(rowProduct, indexProduct) in totalProduct"
                                :key="indexProduct"
                                width="100%"
                            >
                            <tr>
                                <td>{{ record.items[indexProduct].name }}</td>
                                <td>
                                    <el-select v-model="form[rowProduct]"
                                               placeholder="Almacenes">
                                        <el-option
                                            v-for="item in warehouses"
                                            v-if="rowProduct === item.item_id"
                                            :key="item.id"
                                            :disabled="optionDisable(item.item_id, item.stock)"
                                            :label="item.warehouse + ' - ' + 'Stock -> ' + Math.trunc(item.stock)"
                                            :value="item.id"
                                        ></el-option>
                                    </el-select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click="close">Cerrar</el-button>
                <el-button type="primary"
                           @click="save">Guardar
                </el-button>
            </div>
        </el-dialog>

        <options-form
            :recordId="documentNewId"
            :resource="resource_options"
            :showDialog.sync="showDialogOptions"
            :statusDocument="statusDocument"
        ></options-form>

        <document-form ref="document_form"
                       :resource="resource"
                       :document_types="document_types"
                       :order_id="order_id"
                       :user="user">
        </document-form>
        <items-import :showDialog.sync="showImportSetDialog"></items-import>

        <sale-note-form
            :dataSaleNote="dataSaleNote"
            :orderId="order_id"
            :showDialog.sync="showDialogSaleNote"
        >
        </sale-note-form>
    </div>
</template>
<script>
import DataTable from "./partials/DataTable";
import DocumentForm from "./partials/document_form.vue";
import SaleNoteForm from "../../../../../../resources/js/views/tenant/orders/partials/sale_note_form.vue";
import OptionsForm from "../../../../../../resources/js/views/tenant/pos/partials/options.vue";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import ItemsImport from './partials/import.vue'

/*

import OptionsForm from "../pos/partials/options.vue";
*/

export default {
    props: [
        'configuration'
    ],

    components: {
        ItemsImport,
        DataTable,
        DocumentForm,
        OptionsForm,
        SaleNoteForm
    },
    data() {
        return {
            showImportDialog: false,
            showImportSetDialog: false,
            showImageDetail: false,
            resource: "logistic_operator/yobel",
            recordId: null,
            options: [],
            warehouses: [],
            estableciment_id: "",
            totalProduct: [], // items_id
            showDialog: false,
            form: [],
            record: "", // record orders
            stocks: "",
            showDialogOptions: false,
            documentNewId: null,
            statusDocument: {},
            resource_options: null,
            loading_submit: false,
            document_types: [],
            order_id: null,
            dataSaleNote: {},
            user: {},
            showDialogSaleNote: false,
        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        this.user = this.config.user;
    },
    mounted() {
        this.$http.get(`/statusOrder/records`).then(response => {
            this.options = response.data;
        });
        this.events()
    },
    computed: {

        ...mapState([
            'config',
        ]),
    },
    methods: {
        ...mapActions(['loadConfiguration']),
        clickOptions(recordId) {

            this.documentNewId = recordId
            this.statusDocument.send = ""
            this.resource_options = 'sale-notes'
            this.showDialogOptions = true

        },
        async clickDownload(row) {
            await this.$http.get(`/documents/search/externalId/${row}`).then((response) => {
                this.documentNewId = response.data.id
            })
            this.statusDocument.send = ""
            this.resource_options = 'documents'
            this.showDialogOptions = true
        },
        subtotal(item) {
            var subtotal;
            if (item.currency_type_id === "USD") {
                subtotal = Number(
                    item.cantidad *
                    item.exchange_rate_sale *
                    parseFloat(item.sale_unit_price)
                ).toFixed(2);
                if (isNaN(subtotal)) {
                    return "-";
                } else {
                    return subtotal
                }
            } else {
                return parseFloat(item.cantidad * item.sale_unit_price)
            }
        },
        optionDisable(product, stock) {
            for (var i = 0; i < this.record.items.length; i++) {
                if (product === this.record.items[i].id) {
                    return stock >= this.record.items[i].cantidad ? false : true
                }
            }
        },
        openDialogSaleNote(sale_note) {
            this.dataSaleNote = sale_note
            this.showDialogSaleNote = true
        },
        async updateStatus(record) {

            this.record = record

            if (record.status_order_id === 2) {

                this.order_id = record.id

                if (record.purchase.codigo_tipo_documento == '80') {

                    if (record.has_sale_note) return this.$message.success("Ya existe una nota de venta")
                    this.openDialogSaleNote(record.purchase)
                    // console.log(record)

                } else {

                    if (record.document_external_id) {
                        return this.$message.success("Ya existe un comprobante.")
                    }
                    this.$refs.document_form.sendPreview(record.purchase)
                    //this.loading_submit = true
                    //await this.sendDocument(record.purchase)
                }

            } else if (record.status_order_id === 3) {
                this.totalProduct = await this.products(record)
                await this.$http
                    .post(`/orders/warehouse`, {item_id: this.totalProduct})
                    .then(response => {
                        this.warehouses = response.data.data
                        this.showDialog = true
                    });

            } else {
                this.saveUpdateStatus()
            }
        },
        saveUpdateStatus() {
            this.$http.post(`/statusOrder/update`, {record: this.record}).then(response => {
                this.$message.success(response.data.message)
            })
        },
        async save() {
            var save = []

            for (var i = 0; i < this.record.items.length; i++) {
                if (this.totalProduct[i] === this.record.items[i].id) {
                    save.push({
                        id: this.form[this.totalProduct[i]],
                        cantidad: this.record.items[i].cantidad
                    })
                }
            }

            await this.$http
                .post(`/statusOrder/update`, {record: this.record, discount: save})
                .then(response => {
                    this.$message.success(response.data.message)
                    this.close()
                });
        },
        close() {
            this.form = []
            this.showDialog = false
            this.recoard = ""
        },
        products(products) {
            let listProduct = [];

            for (var i = 0; i <= products.items.length - 1; i++) {
                listProduct.push(products.items[i].id)
            }
            return listProduct
        },
        async events() {
            await this.$eventHub.$on("cancelSale", () => {
                this.showDialogOptions = false
            });
        },

        getHeaderConfig() {
            let token = this.user.api_token
            let httpConfig = {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            }
            return httpConfig
        },
        clickImportSet() {
            this.showImportSetDialog = true
        },
        openWindow(url){
            window.open(url,'_blank')
        }

    },

}
</script>
