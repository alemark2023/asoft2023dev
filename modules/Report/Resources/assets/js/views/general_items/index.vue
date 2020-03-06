<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Reporte general de productos</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">F. Emisión</th>
                            <th class="">Tipo Documento</th>
                            <th class="">Serie</th>
                            <th class="">Número</th>
                            <th class="">N° Documento</th>
                            <th class="">Cliente</th>
                            <!-- <th class="">Cod. Interno</th> -->
                            <th class="">Descripción</th>
                            <!-- <th class="">U. Medida</th> -->
                            <th class="">Cantidad</th>
                            <th class="">Total</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td> 
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.document_type_description}}</td>
                            <td>{{row.series}}</td>
                            <td>{{row.alone_number}}</td>
                            <td>{{row.customer_number}}</td>
                            <td>{{row.customer_name}}</td>
                            <!-- <td>{{row.internal_id}}</td> -->
                            <td>{{row.description}}</td>
                            <!-- <td>{{row.unit_type_id}}</td> -->
                            <td>{{row.quantity}}</td>
                            <td>{{row.total}}</td>
                        </tr>
                        
                    </data-table>
                     
                    
                </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTableGeneralItems.vue'

    export default { 
        components: {DataTable},
        data() {
            return {
                resource: 'reports/general-items',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>