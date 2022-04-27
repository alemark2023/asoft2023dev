<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create"  :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <div class="form-body">
            <div class="row" >
                <div class="col-lg-12">
                    <div v-show="this.supplier_id? true : false" class="form-actions text-right pt-2">
                        <el-button 
                                type="primary">Individual</el-button>
                        <el-button
                                native-type="submit"
                                type="primary" @click.prevent="clickAllSales()">Todas las ventas
                        </el-button>
                    </div>
                    <data-table :resource="resource" :form="form">
                        <tr slot="heading">
                            <th>#</th>
                            <th class="text-center">Documento</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Precio</th>
                            <th v-if="this.type === 'allSales'" class="text-center">Proveedor</th>  
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td  class="text-center">{{ row.number_full }}</td>
                            <td class="text-center">{{ row.date_of_issue }}</td> 
                            <td class="text-center">{{ row.price }} </td>  
                            <td v-if="this.type === 'allSales'" class="text-center">{{ row.name }} </td>  
                                
                        </tr>
                    </data-table>

                </div>
                
            </div>
        </div>
        
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template> 

<script>
    import DataTable from '../../components/SimpleDataTableParams.vue'

    export default {
        components: {DataTable},
        props: ['showDialog', 'item_id','customer_id', 'supplier_id'],
        data() {
            return {
                titleDialog: 'Historial de ventas',
                loading: false,
                resource: 'pos/history-sales',
                form:{},
                type:null
            }
        },
        async created() {
             
        },
        methods: {
            initForm(){
                this.form = {
                    item_id : this.item_id,
                    customer_id : this.customer_id,
                    supplier_id : this.supplier_id,
                    type:this.type
                }
            },
            async create(){
                await this.initForm()
                await this.$eventHub.$emit('reloadSimpleDataTableParams')
                
            },   
            close() {
                this.$emit('update:showDialog', false)
            },
            clickAllSales() {
                this.type='allSales'
            },
        }
    }
</script>
