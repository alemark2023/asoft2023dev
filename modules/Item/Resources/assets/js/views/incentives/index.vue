<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Incentivos</span></li>
            </ol>
            <div class="right-wrapper pull-right"> 
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de incentivos</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <!-- <th>Cód. Interno</th> -->
                        <th>Producto</th> 
                        <th>Comisión</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <!-- <td>{{ row.internal_id }}</td> -->
                        <td>{{ row.full_description }}</td> 
                        <td>{{ row.commission_amount }}</td>
                        <td class="text-right">
                            <template v-if="typeUser === 'admin'">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Comisión</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>
 

        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import DataTable from '../../../../../../../resources/js/components/DataTable.vue'
    import {deletable} from '../../../../../../../resources/js/mixins/deletable'

    export default {
        props:['typeUser'],
        mixins: [deletable],
        components: {ItemsForm,  DataTable},
        data() {
            return {
                showDialog: false,
                showImportDialog: false,
                showWarehousesDetail: false,
                resource: 'incentives',
                recordId: null,
            }
        },
        created() {
        },
        methods: { 
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            }, 
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
