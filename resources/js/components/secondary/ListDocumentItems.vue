<template>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Descripción</th>
                        <th class="text-center">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in form.items">
                        <td class="text-center"> {{ index + 1}} </td>
                        <td> 
                            {{ row.item.description }}
                            <template v-if="fnIsRestrictedForSale(row.item, form.document_type_id)">
                                <span class="text-danger mt-1 mb-2 d-block">Restringido para venta en CPE</span>
                            </template>
                        </td>
                        <td class="text-center"> {{ row.quantity }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import { fnRestrictSaleItemsCpe } from '@mixins/functions'

export default {
    mixins: [
        fnRestrictSaleItemsCpe
    ],
    props: {
        form: {
            required: true,
        },
        configuration: {
            required: true,
        },
    },
    computed:{
    },
    data() {
        return {
        }
    },
    created() {
    },
    methods: {
    },
}
</script>
