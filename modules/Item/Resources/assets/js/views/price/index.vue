<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div v-if="!editPrice" class="right-wrapper pull-right">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>

            </div>
            <div v-if="editPrice" class="right-wrapper pull-right">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickEdit()"><i class="fa fa-plus-circle"></i> Editar</button>

            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :productType="typeProduct"
                            :resource="resourceItem">
                    <tr slot="heading"
                        width="100%">
                        <th>#</th>
                        <th>ID</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Nombre</th>
                        <th v-if="columns.description.visible">Descripción</th>
                        <th v-if="columns.model.visible">Modelo</th>
                        <th v-if="columns.brand.visible">Marca</th>
                        <th v-if="columns.item_code.visible">Cód. SUNAT</th>
                        <th class="text-right">P.Unitario (Venta)</th>
                        <th v-if="typeUser != 'seller' && columns.purchase_unit_price.visible"
                            class="text-right">
                            P.Unitario (Compra)
                        </th>
                        <th v-if="columns.real_unit_price.visible"
                            class="text-center">P. venta
                        </th>
                        <th class="text-center">Tiene Igv (Venta)</th>
                        <th v-if="columns.purchase_has_igv_description.visible"
                            class="text-center">Tiene Igv (Compra)
                        </th>
                        <th>Afectacion por lista de precio %</th>
                    </tr>

                    <tr></tr>
                    <tr
                        slot-scope="{ index, row }"
                        :class="{ disable_color: !row.active }"
                    >
                        <td>{{ index }}</td>
                        <td>{{ row.id }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.description }}</td>
                        <td v-if="columns.model.visible">{{ row.model }}</td>
                        <td v-if="columns.brand.visible">{{ row.brand }}</td>
                        <td v-if="columns.description.visible">{{ row.name }}</td>
                        <td v-if="columns.item_code.visible">{{ row.item_code }}</td>
                        
                        <td class="text-right">{{ row.sale_unit_price }}</td>
                        <td v-if="typeUser != 'seller' && columns.purchase_unit_price.visible"
                            class="text-right">
                            {{ row.purchase_unit_price }}
                        </td>
                        <td v-if="columns.real_unit_price.visible"
                            class="text-center">
                            {{ row.sale_unit_price_with_igv }}
                        </td>
                        <td class="text-center">
                            {{ row.has_igv_description }}
                        </td>
                        <td v-if="columns.purchase_has_igv_description.visible"
                            class="text-center">
                            {{ row.purchase_has_igv_description }}
                        </td>
                        <td>{{getListUnitPriceRow(row.amount_sale_unit_price)}}</td>
                    </tr>
                </data-table>
            </div>

            <category-form 
                :showDialog.sync="showDialog"
                :recordId="recordId"
                    ></category-form> 
        </div>
    </div>
</template>

<script>

    import CategoryForm from './form.vue' 
    import DataTable from '../../../../../../../resources/js/components/DataTable.vue'
    import {deletable} from '../../../../../../../resources/js/mixins/deletable'
    import {mapActions, mapState} from "vuex";

    export default {
        mixins: [deletable],
        components: {DataTable, CategoryForm},
        data() {
            return {
                title: null,
                showDialog: false,
                resourceItem: "items",
                typeProduct:"PRODUCTS",
                resource: 'price',
                recordId: null,
                price_default:null,
                editPrice:false,
                columns: {
                description: {
                    title: 'Descripción',
                    visible: false
                    },
                    item_code: {
                        title: 'Cód. SUNAT',
                        visible: false
                    },
                    purchase_unit_price: {
                        title: 'P.Unitario (Compra)',
                        visible: false
                    },
                    purchase_has_igv_description: {
                        title: 'Tiene Igv (Compra)',
                        visible: false
                    },
                    model: {
                        title: 'Modelo',
                        visible: false
                    },
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
                    real_unit_price: {
                        title: 'Mostrar el precio de venta total (con el cálculo IGV)',
                        visible: false
                    },
                    extra_data: {
                        title: 'Stock Por datos extra',
                        visible: false
                    },
                },
            }
        },
        mounted() {
            this.$eventHub.$on('reloadListPrice', () => {
                this.reloadListPrice();
            })
        },
        async created() {
            this.title = 'Precios'
            this.$eventHub.$on('reloadListPrice', () => {
                this.reloadListPrice()
            });
            /* await this.reloadListPrice() */
            /* this.$eventHub.$on('reloadListPrice', () => {
                this.reloadListPrice();
            }) */
        },
        methods: { 
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            }, 
            clickEdit() {
                this.recordId = 1
                this.showDialog = true
            }, 
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            reloadListPrice(){
                return this.$http
                .get(`/${this.resource}/priceDefault`)
                .then(response => {
                    console.log(response.data)
                    if(response.data.length>=0){
                        this.price_default=response.data[0].price
                        this.editPrice=true
                    }
                    /* this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    ); */
                })
            },
            async getListUnitPriceRow(unit_price){
                console.log(this.price_default)
                let porcent= this.price_default/100;
                let extra_price = unit_price*porcent;
                let total = parseFloat(unit_price)+extra_price;
                return _.round(total, 6);
            },
        }
    }
</script>
