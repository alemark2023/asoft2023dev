<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Productos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
              <template > <!-- v-if="typeUser === 'admin'" -->
                    <!-- <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>-->
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </template> 
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de productos Tienda Virtual</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-center">P.Unitario (Venta)</th>
                        <th class="text-center">Imagen</th>
                        <th class="text-center">Visible en Tienda</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.description }}</td>
                        <td>{{ row.name }}</td>
                        <td class="text-center">{{ row.sale_unit_price }}</td>
                        <td class="text-center">
                             <img :src="row.image_url_medium" alt="" width="40" height="40"> 
                               <!--<img :src="row.image_url_medium"  width="40" height="40" class="img-thumbail img-custom" /> -->
                        </td>
                        <td class="text-center"> 
                            <el-checkbox @change="visibleStore($event, row.id)" :checked="row.apply_store"></el-checkbox>
                        </td>
                        <td class="text-right">
                            <template > <!-- v-if="typeUser === 'admin'" -->
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>

            <!-- <items-import :showDialog.sync="showImportDialog"></items-import> -->

            <warehouses-detail 
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail">
            </warehouses-detail>

        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import WarehousesDetail from './partials/warehouses.vue'
   // import ItemsImport from './import.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props:[], //'typeUser'
        mixins: [deletable],
        components: {ItemsForm, DataTable, WarehousesDetail}, //ItemsImport
        data() {
            return {
                showDialog: false,
                showImportDialog: false,
                showWarehousesDetail: false,
                resource: 'items',
                recordId: null,
                warehousesDetail:[]
            }
        },
        created() {
        },
        methods: {
            visibleStore(apply_store, id)
            {

                this.$http.post(`/${this.resource}/visible_store`, { id, apply_store })
                    .then(response => {
                        if (response.data.success) {
                           // row.apply_store = 
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        
                    })
                    .then(() => {
                       
                    })
            },
            clickWarehouseDetail(warehouses){
                this.warehousesDetail = warehouses
                this.showWarehousesDetail = true
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickImport() {
                this.showImportDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
