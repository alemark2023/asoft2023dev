<template>
    <div class="card mb-0 pt-2 pt-md-0">

        <div class="card mb-0">
            <div class="card-body">
                <!--<data-table :resource="resource">

                </data-table>-->
            </div>
        </div>
    </div>
</template>

<script>
import DataTable from "../../components/DataTableKardex.vue";

export default {
    components: { DataTable },
    data() {
        return {
            resource: "reports/kardex/lots",
            form: {},
            item_id: null
        };
    },
    created() {
        /*this.$eventHub.$on("emitItemID", item_id => {
            // console.log(item_id)
            this.item_id = item_id;
        });*/
    },
    methods: {}
};
</script>
