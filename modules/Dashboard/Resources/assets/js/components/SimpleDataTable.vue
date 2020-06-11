<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <slot name="heading"></slot>
            </thead>
            <tbody>
                <slot v-for="(row, index) in records" :row="row" :index="customIndex(index)"></slot>
            </tbody>
        </table>
        <div>
            <el-pagination
                    @current-change="getRecords"
                    layout="total, prev, pager, next"
                    :total="pagination.total"
                    :current-page.sync="pagination.current_page"
                    :page-size="pagination.per_page">
            </el-pagination>
        </div>
    </div> 
</template>


<script>

    import moment from 'moment'
    import queryString from 'query-string'

    export default {
        props: {
            resource: String,
        },
        data () {
            return { 
                columns: [],
                records: [],
                pagination: {}
            }
        },
        computed: {
        },
        created() {
            this.$eventHub.$on('reloadSimpleDataTable', () => {
                this.getRecords()                
            }) 
        },
        async mounted () { 
            await this.getRecords()

        },
        methods: {
            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            getRecords() {
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                    this.records = response.data.data
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                    this.$eventHub.$emit('recordsSkeletonLoader', false)
                });
            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit
                })
            } 
        }
    }
</script>