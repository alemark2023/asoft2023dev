<template>
    <div class="row card-table-report">
        <div class="col-md-12">
            <div class="card card-primary" v-loading="loading">
                <div class="card-header">
                    <h4 class="card-title">Consulta de inventarios</h4>
                </div>
                <div class="card-body">
                    <div class="row m-b-10">
                        <div class="col-md-4">
                            <el-select v-model="form.warehouse_id"
                                       @change="changeWarehouse"
                                       placeholder="Seleccionar almacén">
                                <el-option key="all" label="Todos" value="all"></el-option>
                                <el-option v-for="opt in warehouses"
                                           :key="opt.id"
                                           :label="opt.description"
                                           :value="opt.id">
                                </el-option>
                            </el-select>
                        </div>
                        <div class="col-md-3">
                            <el-select v-model="form.filter"
                                       @change="changeFilter"
                                       placeholder="Seleccionar filtro">
                                <el-option key="01" label="Todos" value="01"></el-option>
                                <el-option key="02" label="Stock < 0" value="02"></el-option>
                                <el-option key="03" label="Stock = 0" value="03"></el-option>
                                <el-option key="04" label="0 < Stock <= Stock mínimo" value="04"></el-option>
                                <el-option key="05" label="Stock > Stock mínimo" value="05"></el-option>
                            </el-select>
                        </div>
                        <div class="col-auto">
                            <el-button @click="clickExport('pdf')"
                                       :loading="loadingPdf"
                                       :disabled="records.length <= 0"><i class="fa fa-file-pdf"></i> Exportar PDF
                            </el-button>
                        </div>
                        <div class="col-auto">
                            <el-button @click="clickExport('xlsx')"
                                       :loading="loadingXlsx"
                                       :disabled="records.length <= 0"><i class="fa fa-file-excel"></i> Exportar Excel
                            </el-button>
                        </div>
                    </div>
                    <div class="row" v-if="records.length > 0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-xl table-bordered table-hover">
                                    <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Categoria</th>
                                        <th class="text-right">Stock mínimo</th>
                                        <th class="text-right">Stock actual</th>
                                        <th class="text-right">Precio de venta</th>
                                        <th class="text-right">Costo</th>
                                        <th>Marca</th>
                                        <th class="text-center">F. vencimiento</th>
                                        <th>Almacén</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in records">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.name }}</td>
                                        <td>{{ row.item_category_name }}</td>
                                        <td class="text-right">{{ row.stock_min }}</td>
                                        <td class="text-right">{{ row.stock }}</td>
                                        <td class="text-right">{{ row.sale_unit_price }}</td>
                                        <td class="text-right">{{ row.purchase_unit_price }}</td>
                                        <td>{{ row.brand_name }}</td>
                                        <td class="text-center">{{ row.date_of_due }}</td>
                                        <td>{{ row.warehouse_name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            Total {{ records.length }}
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-md-12">
                            <strong>No se encontraron registros</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    props: [],
    data() {
        return {
            // loading_submit: false,
            // showDialogLots: false,
            // showDialogLotsOutput: false,
            // titleDialog: null,
            loading: false,
            loadingPdf: false,
            loadingXlsx: false,
            resource: 'inventory/report',
            errors: {},
            form: {},
            warehouses: [],
            records: []
        }
    },
    created() {
        this.initTables();
        this.initForm();
    },
    methods: {
        initForm() {
            this.form = {
                'warehouse_id': null,
                'filter': '01'
            }
        },
        initTables() {
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.warehouses = response.data.warehouses;
                });
        },
        async getRecords() {
            this.loading = true;
            this.records = [];
            await this.$http.post(`/${this.resource}/records`, this.form)
                .then(response => {
                    this.records = response.data;
                });
            this.loading = false;
        },
        changeWarehouse() {
            this.getRecords();
        },
        changeFilter() {
            this.getRecords();
        },
        async clickExport(format) {
            this.loading = true;
            // this.loadingSubmit = true;
            this.loadingPdf = (format === 'pdf');
            this.loadingXlsx = (format === 'xlsx');
            this.errors = {};
            await this.$http({
                url: `/${this.resource}/export`,
                method: 'POST',
                responseType: 'blob',
                data: {
                    'records': this.records,
                    'format': format,
                }
            })
                .then(response => {
                    let res = response.data;
                    if(res.type === 'application/json') {
                        this.$message.error('Error al exportar');
                    } else {
                        const url = window.URL.createObjectURL(new Blob([res]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'ReporteInv.' + format);
                        document.body.appendChild(link);
                        link.click();
                    }
                    // if(res.success) {

                    // } else {
                    //     console.log(res);
                    // }
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error;
                })
                .then(() => {
                    this.loadingPdf = false;
                    this.loadingXlsx = false;
                    this.loading = false;
                });
            // this.loadingSubmit = false;
            //

        }
    }
}
</script>
