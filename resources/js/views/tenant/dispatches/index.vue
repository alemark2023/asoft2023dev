<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Guias de remisión</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th class="text-center">Fecha Envío</th>
                        <th class="text-center">Descargas</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{'text-danger': (row.state_type_id === '11')}">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }} <br /> <small>{{ row.customer_number }}</small></td>
                        <td>{{ row.number }}</td>
                        <td class="text-center">{{ row.date_of_shipping }}</td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickDownload(row.download_external_xml)">XML</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickDownload(row.download_external_pdf)">PDF</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickDownload(row.download_external_cdr)">CDR</button>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickOptions(row.id)">Opciones</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
            <dispatch-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"></dispatch-options>
    </div>
</template>

<script>
    import DataTable from '../../../components/DataTable.vue'
    import DispatchOptions from './partials/options.vue'

    export default {

        components: {DataTable, DispatchOptions},
        data() {
            return {
                resource: 'dispatches',
                showDialogOptions: false,
                recordId: null,
            }
        },
        created() {},
        methods: {
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
            clickPrint(external_id){
                window.open(`/print/dispatch/${external_id}/a4`, '_blank');
            },

        }
    }
</script>
