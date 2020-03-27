<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Ingresos y egresos por métodos de pago</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">Método de pago</th>
                            <th class="text-center">T. Pagos CPE</th>
                            <th class="text-center">T. Pagos NV</th>
                            <th class="text-center">T. Pagos Compras</th>
                            <th class="text-center">T. Pagos Gastos</th>
                            <!-- <th class="text-center">Saldo</th> -->
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td> 
                            <td>{{row.description}}</td>
                            <td class="text-center">{{row.document_payment}}</td>
                            <td class="text-center">{{row.sale_note_payment}}</td>
                            <td class="text-center"> {{row.purchase_payment}}</td>
                            <td class="text-center">{{row.expense_payment}}</td>
                            <!-- <td class="text-center">{{row.balance}}</td>  -->
                        </tr>
                    </data-table>
                </div>
        </div>

    </div>
</template>

<script>

    import DataTable from '../../components/DataTableWithoutPaging.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'finances/payment-method-types',
                form: {},

            }
        },
        async created() {
        },
        methods: {


        }
    }
</script>
