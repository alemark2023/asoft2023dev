<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Cajas</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <template  v-if="open_cash">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickDownloadGeneral()"><i class="fas fa-shopping-cart"></i> Reporte general</button>

                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fas fa-shopping-cart"></i> Aperturar caja chica</button>
                </template>
                <!-- <template v-else>                 -->
                    <!-- <button type="button" class="btn btn-success btn-sm  mt-2 mr-2" @click.prevent="clickOpenPos()"><i class="fas fa-shopping-cart" ></i> Aperturar punto de venta</button> -->
                <!-- </template> -->
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de cajas</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th># Referencia</th>
                        <th>Vendedor</th>
                        <th class="text-center">Apertura</th>
                        <th class="text-center">Cierre</th>
                        <th>Saldo inicial</th>
                        <th>Saldo final</th>
                        <!-- <th>Ingreso</th> -->
                        <!-- <th>Egreso</th> -->
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.reference_number }}</td>
                        <td>{{ row.user }}</td>
                        <td class="text-center">{{ row.opening }}</td>
                        <td class="text-center">{{ row.closed }}</td>
                        <td>{{ row.beginning_balance }}</td>
                        <td>{{ row.final_balance }}</td>
                        <!-- <td>{{ row.income }}</td>
                        <td>{{ row.expense }}</td> -->
                        <td>{{ row.state_description }}</td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickDownload(row.id)">Reporte</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickDownloadProducts(row.id)">Reporte Productos</button>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickDownloadIncomeSummary(row.id)">R. Ingreso</button>

                            <template v-if="row.state">

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-warning" @click.prevent="clickCloseCash(row.id)">Cerrar caja</button>
                                <button v-if="typeUser === 'admin'" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button v-if="typeUser === 'admin'" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>

                            </template>

                        </td>
                    </tr>
                </data-table>
            </div>

        </div>
        <cash-form :showDialog.sync="showDialog" :typeUser="typeUser"
                            :recordId="recordId"></cash-form>
    </div>
</template>

<script>

    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'
    import CashForm from './form.vue'

    export default {
        mixins: [deletable],
        components: { DataTable, CashForm},
        props: ['typeUser'],
        data() {
            return {
                showDialog: false,
                open_cash: true,
                resource: 'cash',
                recordId: null,
                cash:null,
            }
        },
        async created() {

            /*await this.$http.get(`/${this.resource}/opening_cash`)
                .then(response => {
                    this.cash = response.data.cash
                    this.open_cash = (this.cash) ? false : true
                })*/

            /*this.$eventHub.$on('openCash', () => {
                this.open_cash = false
            })*/

        },
        methods: {
            clickDownload(id) {
                window.open(`/${this.resource}/report/${id}`, '_blank');
            },
            clickDownloadIncomeSummary(id) {
                window.open(`/${this.resource}/report/income-summary/${id}`, '_blank');
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickCloseCash(recordId) {

                this.recordId = recordId
                const h = this.$createElement;
                this.$msgbox({
                    title: 'Cerrar caja chica',
                    type: 'warning',
                    message: h('p', null, [
                        h('p', { style: 'text-align: justify; font-size:15px' }, '¿Está seguro de cerrar la caja?'),
                    ]),

                    showCancelButton: true,
                    confirmButtonText: 'Cerrar',
                    cancelButtonText: 'Cancelar',
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            this.createRegister(instance, done)
                        } else {
                            done();
                        }
                    }
                    })
                    .then(action => {
                        })
                    .catch(action => {
                    });



            },
            createRegister(instance, done){

                instance.confirmButtonLoading = true;
                instance.confirmButtonText = 'Cerrando caja...';

                this.$http.get(`/${this.resource}/close/${this.recordId}`)
                    .then(response => {
                        if(response.data.success){
                            this.$eventHub.$emit('reloadData')
                            this.open_cash = true
                            this.$message.success(response.data.message)
                        }else{
                            console.log(response)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
                    .then(() => {
                        instance.confirmButtonLoading = false
                        instance.confirmButtonText = 'Iniciar prueba'
                        done()
                    })

            },
            clickOpenPos() {
                window.open('/pos')
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickDownloadGeneral()
            {
                  window.open(`/${this.resource}/report`, '_blank');
            },
            clickDownloadProducts(id)
            {
                window.open(`/${this.resource}/report/products/${id}`, '_blank');

            }
        }
    }
</script>
