<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card mb-0">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr width="100%">
                            <th width="5%">#</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Und</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Lote</th>
                            <th class="text-center">Vcto</th>
                            <th class="text-center">Dias x vencer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.code_item }}</td>
                            <td>{{ row.name_item }}</td>
                            <td>{{ row.und_item }}</td>
                            <!-- <td>{{ row.reference_payment }}</td> -->
                            <td class="text-center">{{ row.quantity }}</td>
                            <td class="text-center">
                                {{ row.code }}
                            </td>
                            <td class="text-center">
                                {{row.date_of_due}}
                            </td>
                            <td class="text-center">
                                {{row.diff_days}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
//import DataTable from "../../components/DataTableKardex.vue";

export default {
  //  components: { DataTable },
    data() {
        return {
            resource: "reports/kardex_lots",
            form: {},
            item_id: null,
            records: []
        };
    },
    created() {

       this.getRecords()
    },
    methods: {
        getRecords()
        {
            return this.$http.get(`/${this.resource}/records`).then((response) => {
                    this.records = response.data.data
                   /* this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                    this.loading_submit = false*/
                });
        }
    }
};
</script>
