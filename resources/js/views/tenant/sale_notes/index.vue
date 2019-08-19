<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Venta</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a href="#" @click.prevent="clickCreate()" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Nota de Venta</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th> 
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Comprobantes</th> 
                        <th class="text-center"></th>
                        <th class="text-center">Descarga</th> 
                        <th class="text-center">Acciones</th> 
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.identifier }} 
                        </td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td> 
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td>
                            <template v-for="(document,i) in row.documents">
                                <label :key="i" v-text="document.number_full" class="d-block"></label>
                            </template>
                        </td>
                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickPayment(row.id)"  v-if="row.btn_payments">Pagos</button>
                        </td>
                        
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)">PDF</button>
                        </td> 
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickCreate(row.id)" v-if="row.btn_generate">Editar</button>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickGenerate(row.id)" v-if="!row.changed">Generar comprobante</button>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptions(row.id)">Opciones</button>
                        </td>

                         
                    </tr>
                </data-table>
            </div>
        </div>

        <sale-note-payments :showDialog.sync="showDialogPayments"
                            :documentId="recordId"></sale-note-payments>

        <sale-notes-options :showDialog.sync="showDialogOptions"
                          :recordId="saleNotesNewId" 
                          :showClose="true"></sale-notes-options> 

        <sale-note-generate :showDialog.sync="showDialogGenerate"
                           :recordId="recordId"
                           :showGenerate="true"
                           :showClose="false"></sale-note-generate>

    </div>
</template>

<script>
  
    import DataTable from '../../../components/DataTable.vue'
    import SaleNotePayments from './partials/payments.vue'
    import SaleNotesOptions from './partials/options.vue'
    import SaleNoteGenerate from './partials/option_documents'

    export default { 
        components: {DataTable, SaleNotePayments, SaleNotesOptions, SaleNoteGenerate},
        data() {
            return { 
                resource: 'sale-notes',
                showDialogPayments: false,
                showDialogOptions: false,
                showDialogGenerate: false,
                saleNotesNewId: null,
                recordId: null,
                showDialogOptions: false
            }
        },
        created() {
        },
        methods: { 
            clickDownload(external_id) {
                window.open(`/downloads/saleNote/sale_note/${external_id}`, '_blank');                
            },  
            clickOptions(recordId) {
                this.saleNotesNewId = recordId
                this.showDialogOptions = true
            },
            clickGenerate(recordId) {
                this.recordId = recordId
                this.showDialogGenerate = true
            },
            clickPayment(recordId) {
                this.recordId = recordId;
                this.showDialogPayments = true;
            },
            clickCreate(id = '') {
                location.href = `/${this.resource}/create/${id}`
            }

        }
    }
</script>
