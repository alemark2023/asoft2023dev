<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"
                > 
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Imprimir A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('a4')">
                        <i class="fa fa-print"></i>
                    </button>
                </div> 
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Imprimir Ticket</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('ticket')">
                        <i class="fa fa-print"></i>
                    </button>
                </div> 
            </div> 
            <br>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Descargar A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload('a4')">
                        <i class="fa fa-download"></i>
                    </button>
                </div> 
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Descargar Ticket</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload('ticket')">
                        <i class="fa fa-download"></i>
                    </button>
                </div> 
            </div>   
                

            <span slot="footer" class="dialog-footer">
                <el-button @click="clickClose">Cerrar</el-button>         
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
                resource: 'quotations', 
                form: {},                 
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {  
                this.form = {
                    id: null,
                    external_id: null, 
                }
            },     
            create() {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = `Cotización registrada: ${this.form.identifier}`
                    })
            },             
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm() 
            },
            clickToPrint(format){
                window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
            } , 
            clickDownload(format){
                window.open(`/${this.resource}/download/${this.form.external_id}/${format}`, '_blank');
            } 
        }
    }
</script>