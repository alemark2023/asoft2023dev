<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de Compras</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">Tipo Documento</th>
                            <th class="">Número</th>
                            <th class="">F. Emisión</th>
                            <th class="">F. Vencimiento</th>

                            <th class="">Proveedor</th>
                            <th class="">F. Pago</th>
                            <th class="">Estado</th>
                            <th class="" >T.Exonerado</th>

                            <th class="" >T.Inafecta</th>
                            <th class="" >T.Gratuito</th>
                            <th class="">Total Gravado</th>
                            <th class="">Total IGV</th>
                            <th class="">Total</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td> 
                            <td>{{row.document_type_description}}</td>
                            <td>{{row.number}}</td>
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.date_of_due}}</td>
                            <td>{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td> 
                            <td>{{row.payment_method_type_description}}</td>
                            <td>{{row.state_type_description}}</td>
                            <td>{{ row.total_exonerated}}</td>

                            <td>{{ row.total_unaffected}}</td>
                            <td>{{ row.total_free}}</td>
                            <td>{{ row.state_type_id == '11' ? 0 : row.total_taxed}}</td>
                            <td>{{ row.state_type_id == '11' ? 0 : row.total_igv}}</td>
                            <td>{{ row.state_type_id == '11' ? 0 : row.total}}</td>

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
                resource: 'reports/purchases',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>