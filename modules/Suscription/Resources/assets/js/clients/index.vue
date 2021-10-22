<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Clientes</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button class="btn btn-custom btn-sm  mt-2 mr-2"
                        type="button"
                        @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de clientes</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-right">Número</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td class="text-right">{{ row.number }}</td>
                        <td class="text-right">
                            <button class="btn waves-effect waves-light btn-xs btn-info"
                                    type="button"
                                    @click.prevent="clickCreate(row.id)">Editar
                            </button>
                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                    type="button"
                                    @click.prevent="clickDelete(row.id)">Eliminar
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <customers-form :recordId="recordId"
                            :showDialog.sync="showDialog"></customers-form>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

import CustomersForm from './form.vue'
import DataTable from '../../../../../../resources/js/components/DataTable.vue'
import {deletable} from '../../../../../../resources/js/mixins/deletable'

export default {
    props: [
        'configurations'
    ],
    mixins: [
        deletable
    ],
    components: {
        CustomersForm,
        DataTable
    },
    data() {
        return {
            showDialog: false,
            resource: 'suscription/client',
            recordId: null,
        }
    },
    computed: {
        ...mapState([
            'config',
        ]),
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
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
