<template>
    <div>
        <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Dashboard</span></li>
            </ol>
        </header>
        <div class="row">
            <div class="col-lg-8 mb-3">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="chart-data-selector ready pl-3 pr-4 pt-4">
                                    <div class="chart-data-selector-items">
                                        <chart-line :data="dataChartLine" v-if="loaded"></chart-line>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row px-4 mt-2 pb-3">
                            <div class="col-2 font-weight-bold text-primary">
                                2019
                            </div>
                            <div class="col-10 font-weight-semibold text-right">
                                Comprobantes generados por mes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <section class="card card-horizontal">
                            <header class="card-header bg-success">
                                <div class="card-header-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mx-4">Total Clientes</p>
                                <h2 class="font-weight-semibold mt-0">{{ records.length }}</h2>
                                <div class="summary-footer">
                                    <a class="text-muted text-uppercase" href="#client-list">Ver todos</a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-12">
                        <section class="card card-horizontal">
                            <header class="card-header bg-info">
                                <div class="card-header-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mt-3">Total Comprobantes</p>
                                <h2 class="font-weight-semibold mt-0 mb-3">{{ total_documents }}</h2>
                            </div>
                        </section>
                    </div>
                    <!--<div class="col-xl-6">-->
                        <!--<section class="card card-horizontal">-->
                            <!--<header class="card-header bg-secondary">-->
                                <!--<div class="card-header-icon">-->
                                    <!--<i class="fas fa-dollar-sign"></i>-->
                                <!--</div>-->
                            <!--</header>-->
                            <!--<div class="card-body p-4 text-center">-->
                                <!--<p class="font-weight-semibold mb-0 mt-3">Total Venta en Planes</p>-->
                                <!--<h2 class="font-weight-semibold mt-0 mb-3">2</h2>-->
                            <!--</div>-->
                        <!--</section>-->
                    <!--</div>-->
                </div>
                <!--<div class="row">-->
                    <!--<div class="col-xl-6">-->
                        <!--<section class="card card-horizontal">-->
                            <!--<header class="card-header bg-megna">-->
                                <!--<div class="card-header-icon">-->
                                    <!--<i class="fas fa-file"></i>-->
                                <!--</div>-->
                            <!--</header>-->
                            <!--<div class="card-body p-4 text-center">-->
                                <!--<p class="font-weight-semibold mb-0 mt-3">Total Facturas</p>-->
                                <!--<h2 class="font-weight-semibold mt-0 mb-3">{{ total_documents }}</h2>-->
                            <!--</div>-->
                        <!--</section>-->
                    <!--</div>-->
                    <!--<div class="col-xl-6">-->
                        <!--<section class="card card-horizontal">-->
                            <!--<header class="card-header bg-info">-->
                                <!--<div class="card-header-icon">-->
                                    <!--<i class="fas fa-file-alt"></i>-->
                                <!--</div>-->
                            <!--</header>-->
                            <!--<div class="card-body p-4 text-center">-->
                                <!--<p class="font-weight-semibold mb-0 mt-3">Total Comprobantes</p>-->
                                <!--<h2 class="font-weight-semibold mt-0 mb-3">{{ total_documents }}</h2>-->
                            <!--</div>-->
                        <!--</section>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div>

        <div class="card" id="client-list">
            <div class="card-header bg-info">
                Listado de Clientes
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2 mb-3" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Hostname</th>
                            <th>Nombre</th>
                            <th>RUC</th>
                            <th>Plan</th>
                            <th>Correo</th>
                            <th class="text-center">Comprobantes</th>
                            <th class="text-center">Usuarios</th>
                            <th class="text-center">F.Creación</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.hostname }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.number }}</td>
                            <td>{{ row.plan }}</td>
                            <td>{{ row.email }}</td>
                            <td class="text-center">{{ row.count_doc }}/
                                <template v-if="row.max_documents > 9999"><i class="fas fa-infinity"></i></template>
                                <template v-else>{{ row.max_documents }}</template>
                            </td>
                            <td class="text-center">{{ row.count_user }}/
                                <template v-if="row.max_users > 9999"><i class="fas fa-infinity"></i></template>
                                <template v-else>{{ row.max_users }}</template>
                            </td>
                            <td class="text-center">{{ row.created_at }}</td>
                            <td class="text-right">
                                <template v-if="!row.locked">
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-info m-1__2" @click.prevent="clickPassword(row.id)">Resetear clave</button>
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger m-1__2" @click.prevent="clickDelete(row.id)">Eliminar</button>
                                </template>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <system-clients-form :showDialog.sync="showDialog"
                             :recordId="recordId"></system-clients-form>
    </div>
</template>

<script>

    import CompaniesForm from './form.vue'
    import {deletable} from "../../../mixins/deletable"
    import {changeable} from "../../../mixins/changeable"
    import ChartLine from './charts/Line'

    export default {
        mixins: [deletable,changeable],
        components: {CompaniesForm, ChartLine},
        data() {
            return {
                showDialog: false,
                resource: 'clients',
                recordId: null,
                records: [],
                loaded: false,
                year: 2019,
                total_documents: 0,
                dataChartLine : {
                    labels: null,
                    datasets: [
                        {
                            // label: 'Data One',
                            // backgroundColor: '#f87979',
                            data: null
                        }
                    ]
                }
            }
        },
        async mounted() {
            this.loaded = false
            await this.$http.get(`/${this.resource}/charts`)
                .then(response => {
                    let line = response.data.line
                    this.dataChartLine.labels = line.labels
                    this.dataChartLine.datasets[0].data = line.data
                    this.total_documents = response.data.total_documents
                    // console.log(response.data)
                    // this.records = response.data.data
                })
            this.loaded = true
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })

            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickPassword(id) {
                this.change(`/${this.resource}/password/${id}`)
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
