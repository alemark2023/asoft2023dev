<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Certificado</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive" v-if="record.certificate">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Archivo</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ record.certificate }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                    @click.prevent="clickDelete">Eliminar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row" v-else>
                <div class="col-md-12 text-center">
                    <el-button type="primary" icon="el-icon-plus" @click="clickCreate">Subir</el-button>
                </div>
            </div>
        </div>
        <certificates-form :showDialog.sync="showDialog"
                           :recordId="recordId"></certificates-form>
    </div>
</template>

<script>

    import CertificatesForm from './form.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {CertificatesForm},
        data() {
            return {
                showDialog: false,
                resource: 'certificates',
                recordId: null,
                record: {},
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/record`)
                    .then(response => {
                        this.record = response.data
                    })
            },
            clickCreate() {
                this.showDialog = true
            },
            clickDelete() {
                this.destroy(`/${this.resource}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
