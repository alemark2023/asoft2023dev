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
                            placeholder="Oficina"
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
                            <th></th>
                            <th>Asunto</th>
                            <th>Fecha/Hora registro</th>
                            <th>Remitente</th>
                            <th>Proceso</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in items" :key="item.id">
                            <td class="text-right">{{ item.id }}</td>
                            <td>{{ item.subject }}</td>
                            <td>{{ item.date_register }} - {{ item.time_register }}</td>
                            <td>{{ item.sender.name }}</td>
                            <td>{{ item.documentary_process.name }}</td>
                            <td class="text-center td-btns">
                                <el-dropdown trigger="click">
                                    <el-button>
                                        Opciones
                                        <i class="el-icon-arrow-down el-icon--right"></i>
                                    </el-button>
                                    <el-dropdown-menu slot="dropdown">
                                        <el-dropdown-item
                                            :disabled="loading"
                                            @click.native="onShowModalDerive(item)"
                                        >
                                            <i class="fa fa-file-export"></i>
                                            <span class="ml-3">Derivar</span>
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
            :file="file"
            :visible.sync="openModalAddEdit"
            @onAddItem="onAddItem"
            @onUpdateItem="onUpdateItem"
        ></ModalAddEdit>
        <ModalDerive
            :file="file"
            :visible.sync="showModalDerive"
            @onAddOffice="onAddOffice"
        ></ModalDerive>
    </div>
</template>

<script>
import ModalAddEdit from "./ModalAddEdit";
import ModalDerive from "./ModalDerive";
import moment from "moment";

export default {
    props: {
        files: {
            type: Array,
            required: true,
        },
        offices: {
            type: Array,
            required: true,
        },
    },
    components: {
        ModalAddEdit,
        ModalDerive,
    },
    data() {
        return {
            showModalDerive: false,
            items: [],
            file: null,
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
    mounted() {
        this.items = this.files;
    },
    methods: {
        onShowExtraButtons(file) {
            if (file.offices) {
                if (file.offices.length > 0) {
                    return false;
                }
            }
            return true;
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
        },
        onDelete(item) {
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
                    this.$http
                        .delete(`${this.basePath}/${item.id}/delete`)
                        .then((response) => {
                            this.$message({
                                type: "success",
                                message: response.data.message,
                            });
                            this.items = this.items.filter((i) => i.id !== item.id);
                        })
                        .catch((error) => {
                            this.axiosError(error);
                        });
                })
                .catch();
        },
        onEdit(item) {
            this.file = {...item};
            this.openModalAddEdit = true;
        },
        onUpdateItem(data) {
            this.items = this.items.map((i) => {
                if (i.id === data.id) {
                    return data;
                }
                return i;
            });
        },
        onAddItem(data) {
            this.items.unshift(data);
        },
        onCreate() {
            this.file = null;
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
