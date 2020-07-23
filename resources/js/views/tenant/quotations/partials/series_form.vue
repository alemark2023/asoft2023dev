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
                                            <button :disabled="!row.item.lots || row.item.lots.length == 0" type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="openDialogLots(row.item.lots)">
                                                    <i class="el-icon-check"></i> Series
                                            </button>
                                        </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                </div>
            </div>

            <lots :showDialog.sync="showDialogLots" :lots="lots"></lots>
        </div>

</template>

<script>
import Lots from "./lots.vue";

export default {
    props:['items'],
    components:{Lots},
    data()
    {
        return{
            showDialogLots: false,
            lots:[]
        }
    },
    created(){

    },
    methods:{
        openDialogLots(lt)
        {
            this.showDialogLots = true
            this.lots = lt
        },
        previewItems()
        {

        }

    }
}
</script>

<style>

</style>
