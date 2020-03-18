<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Certificado</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive" v-if="record">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Archivo</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ record }}</td>
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
                    <el-button  type="primary" icon="el-icon-plus" @click="clickCreate">Subir</el-button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Usuario SOAP</th>
                        <th>Password SOAP</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td> <el-input v-model="soap_username"></el-input> </td>
                        <td> <el-input v-model="soap_password"></el-input> </td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickSaveSoapUser">Guardar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                soap_username:null,
                soap_password:null,

            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            clickSaveSoapUser()
            {
                 let soap_username = this.soap_username
                 let soap_password = this.soap_password

                  this.$http.post(`${this.resource}/saveSoapUser`, { soap_username, soap_password})
                    .then(response => {
                        if (response.data.success) {

                             this.$message.success(response.data.message)

                        } else {
                            
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {

                        this.$message.error("Sucedio un error.");

                    })
                    .then(() => {

                    })

            },
            getData() {
                this.$http.get(`/${this.resource}/record`)
                    .then(response => {
                        this.record = response.data.certificate
                        this.soap_username = response.data.soap_username
                        this.soap_password = response.data.soap_password
                        //this.config_system_env = response.data.config_system_env
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
