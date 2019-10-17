<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Análisis comercial</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                            <tr slot="heading">
                            <th class="">#</th>
                            <th class="">Cliente</th>
                            <th class="">Documento</th>
                            <th class="">Zona</th>
                            <th class="">Celular</th> 
                            <th class="">Primera compra</th> 
                            <th class="">Ultima compra</th> 
                            <!-- <th class="">T. Prom Dos compras en día</th> 
                            <th class="">Cant. Visita</th>  -->
                            <!-- <th class="">Total</th>  -->
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td> 
                            <td>{{row.customer_name}}</td>
                            <td>{{ row.customer_doc }} - {{ row.customer_number }}<br/></td> 
                            <td>{{row.zone}}</td> 
                            <td>{{row.telephone}}</td> 
                            <td>{{(row.first_document) ? row.first_document.series  + '-'+ row.first_document.number :'-'}}</td> 
                            <td>{{(row.last_document) ? row.last_document.series  + '-'+ row.last_document.number :'-'}}</td> 

                          
                        </tr>
                        
                    </data-table>
                     
                    
                </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTableCommercialAnalysis.vue'

    export default { 
        components: {DataTable},
        data() {
            return {
                resource: 'reports/commercial-analysis',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>