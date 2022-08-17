<template>
    <el-dialog :visible="showDialog" @close="close" @open="getData" width="65%" :close-on-click-modal="false" :close-on-press-escape="false">
        <span slot="title" class="d-flex justify-content-between h5 p-3"> {{title}}
            <el-button type="primary" icon="el-icon-plus" @click="clickAddRow">Agregar Despacho</el-button>
        </span>
        <!-- <div class="col-md-12 text-center pt-2" v-if="showAddButton && (document.total_difference > 0)">
            
        </div> -->
        <div class="form-body">
            <div class="row">
                <div class="col-md-12" v-if="records.length > 0">
                    <div class="table-responsive table-sm">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de despacho</th>
                                <th>Hora de despacho</th>
                                <th>Persona quien recogio</th>
                                <th>Referencia</th>
                                <th>Personal que despacho</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records" :key="index">
                                <template v-if="row.id">
                                    <td>DESPACHO-{{ row.id }}</td>
                                    <td>{{ row.date_of_payment }}</td>
                                    <td>{{ row.hour_of_payment }}</td>
                                    <td>{{ row.destination_description }}</td>
                                    <td>{{ row.reference }}</td>
                                    <td class="text-right">{{ row.payment }}</td>
                                    <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)"><i class="fas fa-trash"></i></button>
                                        <!--<el-button type="danger" icon="el-icon-delete" plain @click.prevent="clickDelete(row.id)"></el-button>-->
                                    </td>
                                </template>
                                <template v-else>
                                    <td></td>
                                    <td>{{ row.date_of_payment }}</td>
                                    <td>{{ row.hour_of_payment }}</td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.person_dispatch}">
                                            <el-input v-model="row.person_dispatch"></el-input>
                                            <small class="form-control-feedback" v-if="row.errors.person_dispatch" v-text="row.errors.person_dispatch[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.reference}">
                                            <el-input v-model="row.reference"></el-input>
                                            <small class="form-control-feedback" v-if="row.errors.reference" v-text="row.errors.reference[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.payment}">
                                            <el-input v-model="row.payment"></el-input>
                                            <small class="form-control-feedback" v-if="row.errors.payment" v-text="row.errors.payment[0]"></small>
                                        </div>
                                    </td>
                                    <td class="series-table-actions text-right">
                                        
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 pt-2">
                    <div class="d-flex">
                        <div class="d-flex">
                            <div class="d-flex flex-column">
                                <el-checkbox v-model="search_by_plate" :disabled="recordItem != null">
                                    Entregado
                                </el-checkbox>
                                <el-checkbox v-model="search_by_plate" :disabled="recordItem != null">
                                    Parcial
                                </el-checkbox>
                            </div>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-light" @click.prevent="clickSubmit(index)">Borrar Check</button>
                        </div>
                        <div class="w-100 text-center">
                            <button type="button" class="btn waves-effect waves-light btn btn-info" @click.prevent="clickSubmit(index)">Grabar</button>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </el-dialog>

</template>

<style>
.el-upload-list__item-name [class^="el-icon"] {
    display: none;
}
.el-upload-list__item-name {
    margin-right: 25px;
}
.el-upload-list__item {
    font-size: 10px;
}
</style>

<script>

    import {deletable} from '../../../../mixins/deletable'

    export default {
        props: ['showDialog', 'documentId'],
        mixins: [deletable],
        data() {
            return {
                title: null,
                resource: 'sale_note_payments',
                records: [],
                payment_destinations: [],
                payment_method_types: [],
                headers: headers_token,
                index_file: null,
                fileList: [],
                showAddButton: true,
                document: {}
            }
        },
        async created() {
            await this.initForm();
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.payment_destinations = response.data.payment_destinations
                    this.payment_method_types = response.data.payment_method_types;
                    //this.initDocumentTypes()
                })
        },
        methods: {
            clickDownloadFile(filename) {
                window.open(
                    `/finances/payment-file/download-file/${filename}/sale_notes`,
                    "_blank"
                );
            },
            onSuccess(response, file, fileList) {

                // console.log(response, file, fileList)
                this.fileList = fileList

                if (response.success) {

                    this.index_file = response.data.index
                    this.records[this.index_file].filename = response.data.filename
                    this.records[this.index_file].temp_path = response.data.temp_path

                } else {
                    this.cleanFileList()
                    this.$message.error(response.message)
                }

                // console.log(this.records)

            },
            cleanFileList(){
                this.fileList = []
            },
            handleRemove(file, fileList) {

                this.records[this.index_file].filename = null
                this.records[this.index_file].temp_path = null
                this.fileList = []
                this.index_file = null

            },
            initForm() {
                this.records = [];
                this.fileList = [];
                this.showAddButton = true;
            },
            async getData() {
                this.initForm();
                await this.$http.get(`/${this.resource}/document/${this.documentId}`)
                    .then(response => {
                        this.document = response.data;
                        this.title = 'Estado de despacho del comprobante: '+this.document.number_full;
                    });
                await this.$http.get(`/${this.resource}/records/${this.documentId}`)
                    .then(response => {
                        this.records = response.data.data
                    });
                this.$eventHub.$emit('reloadDataUnpaid')

            },
            clickAddRow() {
                this.records.push({
                    id: null,
                    date_of_payment: new Date().toLocaleDateString(),
                    hour_of_payment: new Date().toLocaleTimeString(),
                    person_dispatch: null,
                    payment_method_type_id: null,
                    payment_destination_id:null,
                    reference: null,
                    filename: null,
                    temp_path: null,
                    payment: 0,
                    errors: {},
                    loading: false
                });
                this.showAddButton = false;
            },
            clickCancel(index) {
                this.records.splice(index, 1);
                this.showAddButton = true;
                this.fileList = []
            },
            clickSubmit(index) {
                if(this.records[index].payment > parseFloat(this.document.total_difference)) {
                    this.$message.error('El monto ingresado supera al monto pendiente de pago, verifique.');
                    return;
                }
                let paid = false
                if( parseFloat(this.records[index].payment) == parseFloat(this.document.total_difference))
                {
                    paid = true
                }


                let form = {
                    id: this.records[index].id,
                    sale_note_id: this.documentId,
                    date_of_payment: this.records[index].date_of_payment,
                    payment_method_type_id: this.records[index].payment_method_type_id,
                    payment_destination_id: this.records[index].payment_destination_id,
                    reference: this.records[index].reference,
                    filename: this.records[index].filename,
                    temp_path: this.records[index].temp_path,
                    payment: this.records[index].payment,
                    paid: paid
                };
                this.$http.post(`/${this.resource}`, form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message);
                            this.getData();
                            // this.initDocumentTypes()
                            this.$eventHub.$emit('reloadData')
                            this.showAddButton = true;
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.records[index].errors = error.response.data;
                        } else {
                            console.log(error);
                        }
                    })
            },
            // filterDocumentType(row){
            //
            //     if(row.contingency){
            //         this.document_types = _.filter(this.all_document_types, item => (item.id == '01' || item.id =='03'))
            //         row.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
            //     }else{
            //         row.document_type_id = null
            //         this.document_types = this.all_document_types
            //     }
            // },
            // initDocumentTypes(){
            //     this.document_types = (this.all_document_types.length > 0) ? this.all_document_types : []
            // },
            close() {
                this.$emit('update:showDialog', false);
                // this.initDocumentTypes()
                // this.initForm()
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>{
                        this.getData()
                        this.$eventHub.$emit('reloadData')
                    }
                    // this.initDocumentTypes()
                )
            }
        }
    }
</script>
