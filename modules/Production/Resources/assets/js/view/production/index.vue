<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Productos Fabricados</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <!--
                <div class="btn-group flex-wrap">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown" aria-expanded="false"><i class="fa fa-upload"></i> Importar <span
                        class="caret"></span></button>
                    <div class="dropdown-menu" role="menu" x-placement="bottom-start"
                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 42px, 0px);">
                        <a class="dropdown-item text-1" href="#" @click.prevent="clickImportSet()">1. Productos
                            compuestos</a>
                        <a class="dropdown-item text-1" href="#" @click.prevent="clickImportSetIndividual()">2. Detalle
                            productos compuestos</a>
                    </div>
                </div>
                -->
                <template
                >
                    <!-- <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button> -->
                    <button
                        type="button"
                        class="btn btn-custom btn-sm  mt-2 mr-2"
                        @click.prevent="clickCreate()"
                    >
                        <!--
                        v-if="can_add_new_product"-->
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </template>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">
                    Productos Fabricados
                </h3>
            </div>
            <!--
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columnsComputed" :key="index">
                            <el-checkbox
                                v-if="column.title !== undefined && column.visible !== undefined"
                                v-model="column.visible"
                            >{{ column.title }}
                            </el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            -->
            <div class="card-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Usuario</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records">
                        <td>{{ index + 1 }}</td>
                        <td>000{{ row.id }}</td>
                        <td >{{ row.user }}</td>
                        <td >{{ row.item_name }}</td>
                        <td >{{ row.quantity }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>
<script>

import ItemsForm from './form.vue'
/*
  import WarehousesDetail from './partials/warehouses.vue'
import ItemsImport from './import.vue'
import ItemsImportSetIndividual from './partials/import_set_individual.vue'
*/
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
//import DataTable from "../../../../../../../resources/js/components/DataTable";
import {deletable} from "../../../../../../../resources/js/mixins/deletable";

export default {
    props: [
        'configuration',
        'typeUser',
    ],
    mixins: [deletable],
    components: {
        ItemsForm,
        // ItemsImport,
       //DataTable,
        // WarehousesDetail,
        // ItemsImportSetIndividual
    },
    computed: {
        ...mapState([
            'config',
        ]),
        columnsComputed: function () {
            return this.columns;
        }
    },
    data() {
        return {
            can_add_new_product: false,
            showDialog: false,
            showImportSetDialog: false,
            showImportSetIndividualDialog: false,
            showWarehousesDetail: false,
            resource: 'production',
            recordId: null,
            warehousesDetail: [],
            // config: {},
            columns: {
                description: {
                    title: 'Descripción',
                    visible: true
                },
                item_code: {
                    title: 'Cód. SUNAT',
                    visible: false
                },
                /*
                purchase_unit_price: {
                    title: 'P.Unitario (Compra)',
                    visible: false
                },
                purchase_has_igv_description: {
                    title: 'Tiene Igv (Compra)',
                    visible: false
                },*/
                model: {
                    title: 'Modelo',
                    visible: false
                },
                /*
                brand: {
                    title: 'Marca',
                    visible: false
                },
                sanitary: {
                    title: 'N° Sanitario',
                    visible: false
                },
                cod_digemid: {
                    title: 'DIGEMID',
                    visible: false
                },

                 */
            },
            pagination: {},
            records: []
        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        if (this.config.is_pharmacy !== true) {
            // delete this.columns.sanitary;
            // delete this.columns.cod_digemid;
        }

        return this.$http
                .get(`/${this.resource}/records`)
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch(error => {})
                .then(() => {
                    this.loading_submit = false;
                });

        //this.canCreateProduct();

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        canCreateProduct() {
            if (this.typeUser === 'admin') {
                this.can_add_new_product = true
            } else if (this.typeUser === 'seller') {
                if (this.config !== undefined && this.config.seller_can_create_product !== undefined) {
                    this.can_add_new_product = this.config.seller_can_create_product;
                }
            }
            return this.can_add_new_product;
        },
        clickImportSetIndividual() {
            this.showImportSetIndividualDialog = true
        },
        clickWarehouseDetail(warehouses) {
            this.warehousesDetail = warehouses
            this.showWarehousesDetail = true
        },
        clickCreate(recordId = null) {
            window.location.href = `./${this.resource}/create`;


            // this.recordId = recordId
            // this.showDialog = true
        },
        clickImportSet() {
            this.showImportSetDialog = true
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        }
    }
}
</script>
