<template> 
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create"  @close="close"   append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12" v-if="warehouses">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Seleccionar
                                        <el-tooltip class="item" effect="dark" content="Seleccionar almacén desde el cuál se descontará stock" placement="top">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </th>
                                    <th>Ubicación</th>
                                    <th class="text-right">Stock</th>
                                </tr>
                            </thead>
                            <tbody>    
                                <tr v-for="(row,index) in warehouses" :key="index">
                                    <th align="center">
                                        <el-checkbox   v-model="row.checked" @change="changeWarehouse(index)"></el-checkbox>
                                    </th>
                                    <th>{{ row.warehouse_description }}</th>
                                    <th class="text-right">{{ row.stock }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
 

    export default { 
        props:['showDialog', 'warehouses'],
        data() {
            return {
                showImportDialog: false,
                resource: 'items',
                recordId: null,
                titleDialog: 'Almacenes/Stock',
                my_warehouses:[]

            }
        },
        created() {
            // console.log(this.typeUser)
        },
        methods: {
            create(){

            },
            async changeWarehouse(index){

                await this.warehouses.forEach((it, ind) => {
                    // console.log(ind, index)
                    if(ind != index){
                        it.checked = false
                    }
                });

            },
            async close() {

                let warehouse_id = null

                await this.warehouses.forEach((it, ind) => {
                    if(it.checked){
                        warehouse_id = it.warehouse_id
                    }
                });

                if(!warehouse_id)
                    return this.$message.error('Debe seleccionar un almacén');

                await this.$eventHub.$emit('selectWarehouseId', warehouse_id) 
                await this.$emit('update:showDialog', false)
            },
        }
    }
</script>
