<template>
    <el-dialog :title="titleDialog" width="70%" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.series_id}">
                            <label class="control-label">Serie <span class="text-danger">*</span></label>
                            <el-select v-model="form.series_number">
                                <el-option v-for="option in series" :key="option.id" :value="option.number" :label="option.number"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.series_id" v-text="errors.series_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Numero Inicial <span class="text-danger">*</span></label>
                            <el-input v-model="form.initInterval"></el-input>
                            <small class="form-control-feedback" v-if="errors.initInterval" v-text="errors.initInterval[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Numero Final</label>
                            <el-input v-model="form.endInterval"></el-input>
                            <small class="form-control-feedback" v-if="errors.endInterval" v-text="errors.endInterval[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                            <label class="control-label">Tipo comprobante <span class="text-danger">*</span></label>
                            <el-select v-model="form.document_type_id" @change="getSeries" popper-class="el-select-document_type" dusk="document_type_id" class="border-left rounded-left border-info">
                                <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.date_of_reference}">
                            <label class="control-label white-space">Fecha de emisión de comprobantes</label>
                            <el-date-picker v-model="form.date_of_reference" type="date" :clearable="false" value-format="yyyy-MM-dd" @change="changeDateOfReference"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_reference" v-text="errors.date_of_reference[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4" v-if="show_summary_status_type">
                        <div class="form-group" :class="{'has-danger': errors.summary_status_type_id}">
                            <label class="control-label">Tipo de estado</label>
                            <el-select v-model="form.summary_status_type_id"
                                        filterable>
                                <el-option v-for="option in summary_status_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.summary_status_type_id" v-text="errors.summary_status_type_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-end justify-content-end pt-2">
                        <div class="form-group">
                            <el-button  type="primary"
                                        @click.prevent="clickSearchDocuments"
                                        dusk="search-documents"
                            >
                                Buscar comprobantes
                            </el-button>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-end justify-content-end pt-2"
                         v-if="form.documents.length > 0"
                    >
                        <el-dropdown :hide-on-click="false" slot="showhide">
                            <el-button type="primary">
                                Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item v-for="(column, index) in columns"
                                                  :key="index">
                                    <el-checkbox
                                        v-model="column.visible">{{ column.title }}</el-checkbox>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
                <div class="row" v-if="form.documents.length > 0">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Número</th>
                                    <th class="text-center">Moneda</th>
                                    <th class="text-right" v-if="columns.total_exportation.visible">T.Exportación</th>
                                    <th class="text-right" v-if="columns.total_free.visible">T.Gratuita</th>
                                    <th class="text-right" v-if="columns.total_unaffected.visible">T.Inafecta</th>
                                    <th class="text-right" v-if="columns.total_exonerated.visible">T.Exonerado</th>
                                    <th class="text-right" v-if="columns.total_charge.visible">T.Cargos</th>
                                    <th class="text-right">T.Gravado</th>
                                    <th class="text-right">T.Igv</th>
                                    <th class="text-right">Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.documents" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ row.number }}<br/>
                                        <small v-text="row.document_type_description"></small><br/>
                                        <small v-if="row.affected_document" v-text="row.affected_document"></small>
                                    </td>
                                    <td class="text-center">{{ row.currency_type_id }}</td>
                                    <td
                                        class="text-right"
                                        v-if="columns.total_exportation.visible"
                                    >
                                        {{ row.total_exportation }}
                                    </td>
                                    <td
                                        class="text-right"
                                        v-if="columns.total_free.visible"
                                    >
                                        {{ row.total_free }}
                                    </td>
                                    <td
                                        class="text-right"
                                        v-if="columns.total_unaffected.visible"
                                    >
                                        {{ row.total_unaffected }}
                                    </td>
                                    <td
                                        class="text-right"
                                        v-if="columns.total_exonerated.visible"
                                    >
                                        {{ row.total_exonerated }}
                                    </td>
                                    <td
                                        class="text-right"
                                        v-if="columns.total_charge.visible"
                                    >
                                        {{ row.total_charge }}
                                    </td>
                                    <td class="text-right">{{ row.total_taxed }}</td>
                                    <td class="text-right">{{ row.total_igv }}</td>
                                    <td class="text-right white-space">{{ row.total }}</td>
                                    <td class="text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveDocument(index)">x</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.documents.length > 0" dusk="save-summary">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>
<script>

    export default {
        props: ['showDialog'],
        data () {
            return {
                loading_submit: false,
                loading_search: false,
                titleDialog: null,
                resource: 'summaries',
                errors: {},
                form: {},
                resource_documents: 'documents',
                document_types: [],
                all_document_types: [],
                all_series: [],
                series: [],
                summary_status_types: [],
                show_summary_status_type: false,
                columns: {
                    total_exportation: {
                        title: 'T.Exportación',
                        visible: false
                    },
                    total_free: {
                        title: 'T.Gratuito',
                        visible: false
                    },
                    total_unaffected: {
                        title: 'T.Inafecto',
                        visible: false
                    },
                    total_exonerated: {
                        title: 'T.Exonerado',
                        visible: false
                    },
                    total_charge: {
                        title: 'T.Cargos',
                        visible: false
                    },
                }
            }
        },
        created() {
            this.getTables()
            this.initForm()
        },
        methods: {
            async getTables(){

                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => {
                        this.summary_status_types = response.data.summary_status_types
                        this.show_summary_status_type = response.data.show_summary_status_type
                    })

            },
            initForm() {
                this.loading_submit = false,
                this.loading_search = false,
                this.errors = {}
                this.form = {
                    id: null,
                    summary_status_type_id: '1',
                    date_of_issue: null,
                    date_of_reference: moment().format('YYYY-MM-DD'),
                    documents: [],
                    initInterval: null,
                    endInterval: null,
                    document_type_id:null,
                    series_number:null,
                }
            },
            async create() {
                this.titleDialog = 'Registrar Resumen'
                this.changeDateOfReference()

                await this.$http.get(`/${this.resource_documents}/tables`).then(response => {
                    console.log(response.data);
                    this.all_document_types = response.data.document_types_summaries;
                    this.all_series = response.data.series;
                    /* this.payment_destinations = response.data.payment_destinations;
                    this.payment_method_types = response.data.payment_method_types;
                    this.sellers = response.data.sellers */
                });

                this.getDocumentId();
            },
            getSeries() {
                this.form.series_id = null
                
                
                if (this.form.document_type_id!='03') {
                    let serie_document = _.filter(this.all_series, {'document_type_id': this.form.document_type_id})
                    this.series = serie_document.filter((serie) => serie.number.startsWith("B"));
                    this.form.series_number = (this.series.length > 0)?this.series[0].number:null;
                }else{
                    this.series = _.filter(this.all_series, {'document_type_id': this.form.document_type_id});
                    this.form.series_number = (this.series.length > 0)?this.series[0].number:null;
                }
            },
            async getDocumentId() {
                this.document_types = this.all_document_types

                this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                await this.getSeries()
            },
            clickSearchDocuments() {
                this.loading_search = true
                this.$http.post(`/${this.resource}/documents`, {
                    'date_of_reference': this.form.date_of_reference,
                    'series_number': this.form.series_number,
                    'document_type_id': this.form.document_type_id,
                    'initInterval': this.form.initInterval,
                    'endInterval': this.form.endInterval
                })
                    .then(response => {

                        if(response.data.success){

                            this.form.documents = response.data.data

                        }else{

                            this.$message.error(response.data.message)

                        }

                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
                    .then(() => {
                        this.loading_search = false
                    })
            },
            changeDateOfReference() {
                this.form.documents = []
            },
            clickRemoveDocument(index) {
                this.form.documents.splice(index, 1)
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            this.$message.error(error.response.data.message)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>

<style>
    .table thead th {
        white-space: nowrap;
    }
    .white-space {
        white-space: nowrap;
    }
    .el-button--primary:focus {
        outline: 5px auto #66b1ff;
    }
</style>