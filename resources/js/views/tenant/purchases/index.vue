<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Compras</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
                <button   @click.prevent="clickImport()" type="button" class="btn btn-custom btn-sm  mt-2 mr-2" ><i class="fa fa-upload"></i> Importar</button>

            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">F. Emisión</th>
                        <th class="text-center">F. Vencimiento</th>
                        <th>Proveedor</th>
                        <th>Estado</th>
                        <th>Número</th>
                        <th>F. Pago</th>
                        <!-- <th>Estado</th> -->
                        <th class="text-center">Moneda</th>
                        <!-- <th class="text-right">T.Exportación</th> -->
                        <th class="text-right">T.Gratuita</th>
                        <th class="text-right">T.Inafecta</th>
                        <th class="text-right">T.Exonerado</th>
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th>Percepcion</th>
                        <th class="text-right">Total</th>
                        <!-- <th class="text-center">Descargas</th> -->
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td class="text-center">{{ row.date_of_due }}</td>
                        <td>{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td>
                        <td>{{row.state_type_description}}</td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.document_type_description"></small><br/>
                        </td>
                        <td>{{ row.payment_method_type_description }}</td>
                        <!-- <td>{{ row.state_type_description }}</td> -->
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <!-- <td class="text-right">{{ row.total_exportation }}</td> -->
                        <td class="text-right">{{ row.total_free }}</td>
                        <td class="text-right">{{ row.total_unaffected }}</td>
                        <td class="text-right">{{ row.total_exonerated }}</td>
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total_perception ? row.total_perception : 0 }}</td>
                        <td class="text-right">{{ row.total   }}</td>
                        <td>

                            <a v-if="row.state_type_id != '11'" :href="`/${resource}/edit/${row.id}`" type="button" class="btn waves-effect waves-light btn-xs btn-info">Editar</a>
                            <button v-if="row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickAnulate(row.id)">Anular</button>
                            <button v-if="row.state_type_id == '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>



                        </td>

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

            <purchase-import :showDialog.sync="showImportDialog"></purchase-import>
        </div>
    </div>
</template>

<script>

    // import DocumentsVoided from './partials/voided.vue'
    // import DocumentOptions from './partials/options.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'
    import PurchaseImport from './import.vue'


    export default {
        mixins: [deletable],
        // components: {DocumentsVoided, DocumentOptions, DataTable},
        components: {DataTable, PurchaseImport},
        data() {
            return {
                showDialogVoided: false,
                resource: 'purchases',
                recordId: null,
                showDialogOptions: false,
                showImportDialog: false
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
            clickAnulate(id)
            {
                this.anular(`/${this.resource}/anular/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickDelete(id)
            {
                this.delete(`/${this.resource}/delete/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
             clickImport() {
                this.showImportDialog = true
            },
        }
    }
</script>
