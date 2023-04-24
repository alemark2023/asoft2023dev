<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button class="btn btn-custom btn-sm  mt-2 mr-2" type="button" @click.prevent="clickExport()"><i
                    class="fa fa-download"></i> Exportar
                </button>
                <button class="btn btn-custom btn-sm  mt-2 mr-2" type="button" @click.prevent="clickImport()"><i
                    class="fa fa-upload"></i> Importar
                </button>
                <button class="btn btn-custom btn-sm  mt-2 mr-2" type="button" @click.prevent="clickCreate()"><i
                    class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-right">Activo</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }" :class="{ disable_color : !row.enabled}">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.description }}</td>
                        <td class="text-right">{{ (row.enabled) ? 'ACTIVO' : 'INACTIVO' }}</td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <div v-if="row.enabled">
                                        <button class="dropdown-item"
                                                @click.prevent="clickCreate(row.id)">Editar
                                        </button>
                                    </div>
                                    <div v-if="typeUser === 'admin'">
                                        <button v-if="row.enabled" class="dropdown-item"
                                                @click.prevent="clickDisable(row.id)">Inhabilitar
                                        </button>
                                        <button v-else class="dropdown-item"
                                                @click.prevent="clickEnable(row.id)">Habilitar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>
            <salud-form 
                :recordId="recordId"
                :showDialog.sync="showDialog"
                :type="type">
            </salud-form>
        </div>
    </div>
</template>
<script>
import SaludForm from './form.vue'
import DataTable from '../components/DataTable.vue'
import {deletable} from '../../../../../../resources/js/mixins/deletable'
export default {
    mixins: [deletable],
    props: ['type', 'typeUser'],
    components: {SaludForm,DataTable},
    data() {
        return {
            title: 'Especialidades',
            showDialog: false,
            showImportDialog: false,
            showExportDialog: false,
            resource: 'salud',
            recordId: null,
        }
    },
    created() {
        // this.getColumnsToShow();
    },
    methods: {
        // getColumnsToShow(updated){
        //     this.$http.post('/validate_columns',{
        //         columns : this.columns,
        //         report : 'client_index', // Nombre del reporte.
        //         updated : (updated !== undefined),
        //     })
        //         .then((response)=>{
        //             if(updated === undefined){
        //                 let currentCols = response.data.columns;
        //                 if(currentCols !== undefined) {
        //                     this.columns = currentCols
        //                 }
        //             }
        //         })
        //         .catch((error)=>{
        //             console.error(error)
        //         })
        // },

        clickCreate(recordId = null) {
            this.recordId = recordId
            this.showDialog = true
        },
        clickImport() {
            this.showImportDialog = true
        },
        clickExport() {
            this.showExportDialog = true
        },
        clickDisable(id) {
            this.disable(`/${this.resource}/enabled/${0}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enabled/${1}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
    }
};
</script>
