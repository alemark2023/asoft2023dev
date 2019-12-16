<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Comisiones vendedores</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                            <th class="text-center">Código vendedor</th>
                            <th>Vendedor</th>
                            <th>Cant transacciones</th>
                            <th>Ventas acumuladas</th>
                            <th>Comisiones</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>  
                            <td>{{row.user_name}}</td>
                            <td>{{row.user_name}}</td>
                            <td>{{row.quantity_transactions}}</td>
                            <td>{{row.acum_sales}}</td> 
                            <td>{{row.total_commissions}}</td> 
                        </tr>
                        
                    </data-table>
                     
                    
                </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTableReports.vue'

    export default { 
        components: {DataTable},
        data() {
            return {
                resource: 'reports/commissions',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>