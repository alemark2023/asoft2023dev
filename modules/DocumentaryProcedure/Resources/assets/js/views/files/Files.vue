<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>EXPEDIENTES</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <div class="btn-group flex-wrap">
                    <button
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        type="button"
                        @click="onCreate"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Todos los expedientes</h3>
            </div>
            <div class="card-body">
                <form class="row" @submit.prevent="onFilter">
                    <div class="col-6 col-md-2 mb-3">
                        <el-input
                            v-model="filter.subject"
                            placeholder="Filtrar por asunto"
                            type="text"
                        />
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <el-select
                            v-model="filter.documentary_office_id"
                            clearable
                            placeholder="Etapa"
                        >
                            <el-option
                                v-for="of in offices"
                                :key="of.id"
                                :label="of.name"
                                :value="of.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <el-select
                            v-model="filter.register_date"
                            clearable
                            placeholder="Fecha de registro"
                            @change="onPrepareFilterDate"
                        >
                            <el-option
                                v-for="(date, i) in datesFilter"
                                :key="i"
                                :label="date"
                                :value="date"
                            ></el-option>
                        </el-select>
                    </div>
                    <div
                        v-if="filter.register_date === 'Personalizado'"
                        class="col-6 col-md-2 mb-3"
                    >
                        <el-date-picker
                            v-model="filter.date_start"
                            format="yyyy/MM/dd"
                            placeholder="Fecha inicial"
                            type="date"
                            value-format="yyyy-MM-dd"
                        >
                        </el-date-picker>
                    </div>
                    <div
                        v-if="filter.register_date === 'Personalizado'"
                        class="col-6 col-md-2 mb-3"
                    >
                        <el-date-picker
                            v-model="filter.date_end"
                            format="yyyy/MM/dd"
                            placeholder="Fecha final"
                            type="date"
                            value-format="yyyy-MM-dd"
                        >
                        </el-date-picker>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <el-button native-type="submit">
                            <i class="fa fa-search"></i>
                            <span class="ml-2">Buscar</span>
                        </el-button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Asunto</th>
                            <th>Fecha/Hora registro</th>
                            <th>Remitente</th>
                            <th>Proceso</th>
                            <th>Etapa</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in items" :key="item.id">
                            <td class="text-right">{{ index + 1}}</td>
                            <td>{{ item.subject }}</td>
                            <td>{{ item.date_register }} - {{ item.time_register }}</td>
                            <td>{{ item.sender.name }}</td>
                            <td>
                                {{ returnProcessName(item) }}
                            </td>
                            <td>
                                {{ returnEtapa(item) }}
                            </td>
                            <td class="text-center td-btns">
                                <el-dropdown trigger="click">
                                    <el-button>
                                        Opciones
                                        <i class="el-icon-arrow-down el-icon--right"></i>
                                    </el-button>
                                    <el-dropdown-menu slot="dropdown">
                                        <!--
                                        <el-dropdown-item
                                            :disabled="loading"
                                            @click.native="onShowModalDerive(item)"
                                        >
                                            <i class="fa fa-file-export"></i>
                                            <span class="ml-3">Derivar</span>
                                        </el-dropdown-item>
                                        -->

                                        <el-dropdown-item
                                            :disabled="loading"
                                            @click.native="onShowModalObservation(item)"
                                        >
                                            <i class="fa fa-file-export"></i>
                                            <span class="ml-3">
                                                Etapas
                                            </span>
                                        </el-dropdown-item>
                                        <el-dropdown-item
                                            v-if="haveObservation(item)"
                                            :disabled="loading"
                                            @click.native="onShowHistoricalObservation(item)"
                                        >
                                            <i class="fa fa-eye"></i>
                                            <span class="ml-3">Histórico de observaciones</span>
                                        </el-dropdown-item>


                                        <template v-if="onShowExtraButtons(item)">
                                            <el-dropdown-item
                                                :disabled="loading"
                                                @click.native="onEdit(item)"
                                            >
                                                <i class="fa fa-edit"></i>
                                                <span class="ml-3">Editar</span>
                                            </el-dropdown-item>
                                            <el-dropdown-item
                                                :disabled="loading"
                                                @click.native="onDelete(item)"
                                            >
                                                <i class="fa fa-trash"></i>
                                                <span class="ml-3">Eliminar</span>
                                            </el-dropdown-item>
                                        </template>
                                    </el-dropdown-menu>
                                </el-dropdown>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ModalAddEdit
            :visible.sync="openModalAddEdit"
            @onAddItem="onAddItem"
            @onUpdateItem="onUpdateItem"
            @onUploadComplete="onUploadComplete"
        ></ModalAddEdit>
        <ModalDerive
            :file="file"
            :visible.sync="showModalDerive"
            @onAddOffice="onAddOffice"
        ></ModalDerive>
        <ModalObservation
            :visible.sync="showModalObservation"
            @onNextStep="onNextStep"
            @onBackStep="onBackStep"
            @updateFiles="updateFiles"
        ></ModalObservation>
        <ModalHistoricalObservation
            :visible.sync="showModalHistoricalObservation"
        ></ModalHistoricalObservation>
    </div>
</template>

<script>
import ModalAddEdit from "./ModalAddEdit";
import ModalDerive from "./ModalDerive";
import ModalHistoricalObservation from "./ModalHistoricalObservation";
import ModalObservation from "./ModalObservation";
import moment from "moment";
import {mapState,mapActions} from "vuex";
import state from "../../../../../../../resources/js/store/state";

export default {
    props: {
        local_files: {
            type: Array,
            required: true,
        },
        local_offices: {
            type: Array,
            required: true,
        },
        local_processes: {
            type: Array,
            required: false,
        },
        local_actions: {
            type: Array,
            required: false,
        },
        local_customers: {
            type: Array,
            required: false,
        },
        local_documentTypes: {
            type: Array,
            required: false,
        },
    },
    computed:{
        ...mapState([
            'offices',
            'file',
            'files',
            'processes',
            'actions',
            'customers',
            'documentTypes',
        ]),
    },
    components: {
        ModalAddEdit,
        ModalDerive,
        ModalHistoricalObservation,
        ModalObservation,
    },
    data() {
        return {
            showModalDerive: false,
            showModalObservation: false,
            showModalHistoricalObservation: false,
            items: [],
            openModalAddEdit: false,
            loading: false,
            filter: {
                name: "",
                register_date: "Hoy",
            },
            basePath: "/documentary-procedure/files",
            datesFilter: [
                "Hoy",
                "Ayer",
                "Anteriores a 7 días",
                "Anteriores a 30 días",
                "Este mes",
                "Mes anterior",
                "Este año",
                "Personalizado",
            ],
        };
    },
    created(){
        this.loadOffices()
        this.loadActions()
        this.loadCustomers()
        this.loadProcesses()
        this.loadFiles()
        this.loadDocumentTypes()

    },
    mounted() {
        this.$store.commit('setOffices',this.local_offices)
        this.$store.commit('setFiles',this.local_files)
        this.$store.commit('setProcesses',this.local_processes)
        this.$store.commit('setActions',this.local_actions)
        this.$store.commit('setCustomers',this.local_customers)
        this.$store.commit('setDocumentTypes',this.local_documentTypes)
        this.items = this.files;

    },
    methods: {
        ...mapActions([
            'loadOffices',
            'loadActions',
            'loadCustomers',
            'loadProcesses',
            'loadDocumentTypes',
            'loadFiles',
        ]),
        haveObservation(item){
            if(item === null) return false;
            if(item === undefined) return false;
            if(item.observations === undefined) return false;
            if(item.observations == null) return false;
            if(item.observations.length == null) return false;
            if(item.observations.length < 1) return false;
              return true
        },
        returnEtapa(item){
            if(
                item !== undefined &&
                item.documentary_office !== undefined &&
                item.documentary_office.name !== undefined
            )return item.documentary_office.name;

            return '';
        },
        returnProcessName(item){
            if(
                item !== undefined &&
                item.documentary_process !== undefined &&
                item.documentary_process.name !== undefined
            )return item.documentary_process.name;

            return '';
        },
        onShowExtraButtons(file) {
            if (file.offices) {
                if (file.offices.length > 0) {
                    return false;
                }
            }
            return true;
        },
        onNextStep(office) {
            this.updateFiles();
        },
        onBackStep(office) {
            this.updateFiles();
        },
        updateFiles() {
            this.loading = true;
            this.$http
                .post(`/documentary-procedure/file/reload`,this.filter)
                .then((result) => {
                    let files = result.data;
                    this.$store.commit('setFiles', files)
                    this.items = this.files
                    this.loading = false;
                }).catch((err)=>{
                this.loading = false;
            })
        },
        onAddOffice(office) {
            this.items = this.items.map((i) => {
                if (i.id === this.file.id) {
                    i.offices.push(office);
                }
                return i;
            });
        },
        onShowModalDerive(file) {
            this.file = file;
            this.showModalDerive = true;
        },
        onShowModalObservation(file) {
            //this.file = file;
            this.$store.commit('setFile',file)
            this.showModalObservation = true;
        },
        onPrepareFilterDate() {
            const date = moment();
            if (this.filter.register_date === "Hoy") {
                this.filter.date_start = date.format("YYYY-MM-DD");
                this.filter.date_end = null;
            }
            if (this.filter.register_date === "Ayer") {
                this.filter.date_start = date.subtract(1, "days").format("YYYY-MM-DD");
                this.filter.date_end = null;
            }
            if (this.filter.register_date === "Anteriores a 7 días") {
                this.filter.date_start = date.subtract(7, "days").format("YYYY-MM-DD");
                this.filter.date_end = moment().format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Anteriores a 30 días") {
                this.filter.date_start = date.subtract(30, "days").format("YYYY-MM-DD");
                this.filter.date_end = moment().format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Este mes") {
                this.filter.date_start = date.startOf("month").format("YYYY-MM-DD");
                this.filter.date_end = date.endOf("month").format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Mes anterior") {
                const prevMonth = date.subtract(1, "month");
                this.filter.date_start = prevMonth
                    .startOf("month")
                    .format("YYYY-MM-DD");
                this.filter.date_end = prevMonth.endOf("month").format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Este año") {
                this.filter.date_start = date.startOf("year").format("YYYY-MM-DD");
                this.filter.date_end = date.endOf("year").format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Personalizado") {
                this.showCalendars = true;
            }
        },
        onFilter() {
            this.updateFiles()
            /*
            this.loading = true;
            const params = this.filter;
            this.$http
                .get(this.basePath, {params})
                .then((response) => {
                    this.items = response.data.data;
                })
                .finally(() => {
                    this.loading = false;
                });
            */
        },
        onDelete(item) {
            this.loading = true;
            this.$confirm(
                `¿estás seguro de eliminar al elemento ${item.subject}?`,
                "Atención",
                {
                    confirmButtonText: "Si, continuar",
                    cancelButtonText: "No, cerrar",
                    type: "warning",
                }
            )
                .then(() => {
                    this.updateFiles()
                    /*
                    this.$http
                        .delete(`${this.basePath}/${item.id}/delete`)
                        .then((response) => {
                            this.$message({
                                type: "success",
                                message: response.data.message,
                            });

                            this.items = this.items.filter((i) => i.id !== item.id);


                            this.loading = false;
                        })
                        .catch((error) => {
                            this.loading = false;
                            this.axiosError(error);
                        });*/
                })
                .catch();
        },
        onEdit(item) {
            // this.file = {...item};
            this.$store.commit('setFile',item)
            this.openModalAddEdit = true;
        },
        onShowHistoricalObservation(item) {
            // this.file = {...item};
            this.$store.commit('setFile',item)
            console.error(item);
            this.showModalHistoricalObservation = true;
        },
        onUpdateItem(data) {
            this.items = this.items.map((i) => {
                if (i.id === data.id) {
                    return data;
                }
                return i;
            });
        },
        onUploadComplete(){

        },
        onAddItem(data) {
            this.updateFiles()
            // this.items.unshift(data);
        },
        onCreate() {
            this.$store.commit('setFile',null)
            this.openModalAddEdit = true;
        },
    },
};
</script>
<style>
.td-btns .el-button {
    margin-top: 3px;
    margin-bottom: 3px;
}
</style>
