<template>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                        <div class="col-md-12">
                            <div style="margin:3px" class="table-responsive">
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
                                    <tr v-for="(row, index) in items" :key="index">
                                        <td class="text-center">{{row.item.description}}</td>
                                        <td class="text-center">{{row.quantity}}</td>
                                        <td class="series-table-actions text-right">
                                            <button  type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="openDialogLots(row.item.lots, row.item_id)">
                                                    <i class="el-icon-check"></i> Series
                                            </button>
                                            <button  type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="openSelectWarehouses(row, index)">
                                                <i class="el-icon-search"></i> Stock
                                            </button>
                                        </td> 
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                </div>
            </div>

            <!-- <lots :showDialog.sync="showDialogLots" :lots="lots"></lots> -->

            <select-lots-form
                :showDialog.sync="showDialogLots"
                :lots="lots"
                :itemId="item_id"
                @addRowSelectLot="addRowSelectLot">
            </select-lots-form>

            <select-warehouses
                :showDialog.sync="showSelectWarehouses"
                :warehouses="selectWarehouses"
                :item_index="item_index">
            </select-warehouses>

        </div>

</template>

<script>
    // import Lots from "./lots.vue";
    import SelectLotsForm from '../../documents/partials/lots.vue'
    import SelectWarehouses from './select_warehouses.vue'

    export default {
        props:['items'],
        components:{SelectLotsForm, SelectWarehouses},

        data()
        {
            return{
                showSelectWarehouses: false,
                selectWarehouses: [],
                showDialogLots: false,
                lots:[],
                item_id: null,
                item_index: -1,
            }
        },
        created(){
            // console.log(this.items)
            
            this.$eventHub.$on('selectWarehouseId', (data) => {
                // console.log(data)
                this.items[data.index].warehouse_id = data.warehouse_id
            })

        },
        methods:{
            openSelectWarehouses(row, index) {

                // console.log(row) 

                this.selectWarehouses = row.item.warehouses
                this.item_index = index
                this.showSelectWarehouses = true

            }, 
            openDialogLots(lt, item_id)
            {
                this.item_id = item_id
                this.showDialogLots = true
                this.lots = lt
            },
            addRowSelectLot(){

            }

        }
    }
</script>
 
