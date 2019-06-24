<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Compras</span></li>
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
                        <th>Proveedor</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">T.Exportación</th>
                        <th class="text-right">T.Gratuita</th>
                        <th class="text-right">T.Inafecta</th>
                        <th class="text-right">T.Exonerado</th>
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right">Total</th>
                        <!-- <th class="text-center">Descargas</th> -->
                        <!-- <th class="text-right">Acciones</th> -->
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.document_type_description"></small><br/> 
                        </td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total_exportation }}</td>
                        <td class="text-right">{{ row.total_free }}</td>
                        <td class="text-right">{{ row.total_unaffected }}</td>
                        <td class="text-right">{{ row.total_exonerated }}</td>
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        
                        <!-- <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                    @click.prevent="clickVoided(row.id)"
                                    v-if="row.btn_voided">Anular</button>
                            <a :href="`/${resource}/note/${row.id}`" class="btn waves-effect waves-light btn-xs btn-warning"
                               v-if="row.btn_note">Nota</a>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickResend(row.id)"
                                    v-if="row.btn_resend">Reenviar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickConsultCdr(row.id)"
                                    v-if="row.btn_consult_cdr">Consultar CDR</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptions(row.id)">Opciones</button>
                        </td> -->
                    </tr>
                </data-table>
            </div>

            <!-- <documents-voided :showDialog.sync="showDialogVoided"
                            :recordId="recordId"></documents-voided>

            <document-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"></document-options> -->
        </div>
    </div>
</template>

<script>

    // import DocumentsVoided from './partials/voided.vue'
    // import DocumentOptions from './partials/options.vue'
    import DataTable from '../../../components/DataTable.vue'

    export default {
        // components: {DocumentsVoided, DocumentOptions, DataTable},
        components: {DataTable},
        data() {
            return {
                showDialogVoided: false,
                resource: 'purchases',
                recordId: null,
                showDialogOptions: false
            }
        },
        created() {
        },
        methods: {
            clickVoided(recordId = null) {
                this.recordId = recordId
                this.showDialogVoided = true
            }, 
            clickDownload(download) {
                window.open(download, '_blank');
            },  
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
        }
    }
</script>
