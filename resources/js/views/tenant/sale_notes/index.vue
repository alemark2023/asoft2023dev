<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Venta</span></li>
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
                        <th>Nota de Venta</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th> 
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right">Total</th>
                        <th class="text-center"></th>
                        <th class="text-center">Descargas</th> 
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
                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickPayment(row.id)">Pagos</button>
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)">PDF</button>
                        </td>
                        
                         
                    </tr>
                </data-table>
            </div>
        </div>

        <sale-note-payments :showDialog.sync="showDialogPayments"
                            :documentId="recordId"></sale-note-payments>

    </div>
</template>

<script>
  
    import DataTable from '../../../components/DataTable.vue'
    import SaleNotePayments from './partials/payments.vue'

    export default { 
        components: {DataTable, SaleNotePayments},
        data() {
            return { 
                resource: 'sale-notes',
                showDialogPayments: false,
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
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            clickPayment(recordId) {
                this.recordId = recordId;
                this.showDialogPayments = true;
            }
        }
    }
</script>
