<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Listado de usuarios</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Nombre</th>
                        <th>Perfil</th>
                        <th>Api Token</th>
                        <th>Establecimiento</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.email }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.type }}</td>
                        <td>{{ row.api_token }}</td>
                        <td>{{ row.establishment_description }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)" v-if="row.id != 1">Eliminar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row" v-if="typeUser != 'integrator'">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div>
        </div>
        <users-form :showDialog.sync="showDialog"
                    :typeUser="typeUser"
                    :recordId="recordId"></users-form>
    </div>
</template>

<script>

    import UsersForm from './form1.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['typeUser'],
        mixins: [deletable],
        components: {UsersForm},
        data() {
            return {
                showDialog: false,
                resource: 'users',
                recordId: null,
                records: [],
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
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
