<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"
                :close-on-click-modal="false"
                :close-on-press-escape="false"
                :show-close="false"> 

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Descargar PDF</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload()">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div> 
               <div class="col-lg-6 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir A5</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('a5')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
            </div> 
            <span slot="footer" class="dialog-footer"> 
                    <el-button @click="clickFinalize">Ir al listado</el-button>
                    <el-button type="primary" @click="clickNewSaleNote">Nueva nota de venta</el-button>
            </span>
        </el-dialog>
 
    </div>
</template>

<script>


    export default { 

        props: ['showDialog', 'recordId', 'showClose'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'sale-notes',
                resource_documents: 'documents',
                errors: {},
                form: {},
                document:{},
                document_types: [],
                all_series: [],
                series: [],
                loading_submit:false,
                showDialogOptions: false,
                documentNewId: null,
                
            }
        },
        created() {
            this.initForm() 
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    external_id: null, 
                    identifier: null,
                    date_of_issue:null
                }
            },      
            create() { 
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = 'Nota de venta registrada: ' + this.form.identifier
                    })
            }, 
            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickNewSaleNote() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            clickDownload(){
                window.open(`/downloads/saleNote/sale_note/${this.form.external_id}`, '_blank');
            },
            clickToPrint(){
                window.open(`/${this.resource}/print/${this.form.id}`, '_blank');
            },
        }
    }
</script>