<template> 
    <section class="card">
        <div class="card-body">
            <h2 class="card-title">Productos por agotarse
                <el-tooltip class="item" effect="dark" content="No aplica filtros" placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h2>
            <simple-data-table :resource="resource">
                <tr slot="heading">
                    <th>#</th>
                    <th >Producto</th>
                    <th class="text-center">Stock</th>
                    <th>Estado</th> 
                    <th>Almacén</th>
                    <th  class="text-center">Aprovisionar</th> 
                <tr>
                <tr slot-scope="{ index, row }">
                    <td>{{ index }}</td>
                    <td  >{{ row.product }}</td>
                    <td class="text-center">{{ row.stock }}</td>
                    <td>
                        <span class="badge bg-danger text-white" v-if="row.state == '01'">Agotado</span>
                        <span class="badge bg-warning text-white" v-if="row.state == '02'">Pocas unidades</span>
                    </td> 
                    <td>{{ row.warehouse }} </td> 
                    <td  class="text-center">
                        <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-primary m-1__2"
                                    @click.prevent="clickProvision()"><i class="fas fa-shopping-cart"></i></button>
                    </td> 
                        
                </tr>
            </simple-data-table>
        </div>
    </section>    
</template>


<script>
 
    import SimpleDataTable from '../../components/SimpleDataTable.vue'

    export default {
        components: {SimpleDataTable},
      
        data () {
            return { 
                resource: 'dashboard/stock-by-product',
                records: [] 
            }
        }, 
        created() { 
        }, 
        methods: {  
            clickProvision(){
                window.open('/purchases/create')
            }
        }
    }
</script>