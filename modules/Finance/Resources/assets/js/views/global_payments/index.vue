<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Pagos globales</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">Destino</th>
                            <th class="">F. Vencimiento</th>
                            <th class="">Proveedor</th>
                            <th class="">Estado</th>
                            <th class="">Número</th>
 
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td>{{row.destination_description}}</td>
                            <td>{{row.date_of_due}}</td>
                            <td>{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td>
                            <td>{{row.state_type_description}}</td>
                            <td>{{row.number}}
                                <small v-text="row.document_type_description"></small><br/>

                            </td> 

                        </tr>
                    </data-table>


                </div>
        </div>

    </div>
</template>

<script>

    import DataTable from '../../components/DataTableGlobalPayment.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'finances/global-payments',
                form: {},

            }
        },
        async created() {
        },
        methods: {


        }
    }
</script>
