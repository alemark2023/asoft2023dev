<template>
    <el-dialog :title="titleDialog"
               :visible="showDialog"
               class="dialog-import"
               @close="close"
               @open="create">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <a href="/formats/item_sets.xlsx"
                           target="_new">Descargar formato</a>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div :class="{'has-danger': errors.file}"
                             class="form-group text-center">
                            <el-upload
                                ref="upload"
                                :auto-upload="false"
                                :headers="headers"
                                :limit="1"
                                :multiple="false"
                                :on-error="errorUpload"
                                :on-success="successUpload"
                                :show-file-list="true"
                                action="/logistic_operator/yobel/items/import">
                                <el-button slot="trigger"
                                           type="primary">Seleccione un archivo (xlsx)
                                </el-button>
                            </el-upload>
                            <small v-if="errors.file"
                                   class="form-control-feedback"
                                   v-text="errors.file[0]"></small>
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           native-type="submit"
                           type="primary">Procesar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

export default {
    props: [
        'showDialog'
    ],
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            titleDialog: null,
            resource: 'items',
            errors: {},
            form: {},
        }
    },
    created() {
        this.initForm()
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                file: null,
            }
        },
        create() {
            this.titleDialog = 'Importar Pedidos de Yobel'
        },
        async submit() {
            this.loading_submit = true
            await this.$refs.upload.submit()
            this.loading_submit = false
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        successUpload(response, file, fileList) {
            if (response.success) {
                this.$message.success(response.message)
                this.$eventHub.$emit('reloadData')
                this.$refs.upload.clearFiles()
                this.close()
            } else {
                this.$message({message: response.message, type: 'error'})
            }
        },
        errorUpload(response) {
            console.log(response)
        }
    }
}
</script>
