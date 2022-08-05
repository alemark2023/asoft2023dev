<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Envío de mensajes WhatsApp Cloud Api
                <el-tooltip
                    class="item"
                    content="Registrarse y obtener los datos de configuración - https://developers.facebook.com/docs/whatsapp/cloud-api/overview"
                    effect="dark"
                    placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h3>
        </div>
        <div class="card-body"> 
            <form autocomplete="off" @submit.prevent="submit">
                <div class="row pt-1">
                    
                    <div class="col-md-12 mt-3">
                        <div class="form-group" :class="{'has-danger': errors.ws_api_phone_number_id}">
                            <label class="control-label">Identificador de número de teléfono <span class="text-danger">*</span></label>
                            <el-input v-model="form.ws_api_phone_number_id"></el-input>
                            <small class="form-control-feedback" v-if="errors.ws_api_phone_number_id" v-text="errors.ws_api_phone_number_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-group" :class="{'has-danger': errors.ws_api_token}">
                            <label class="control-label">Token de acceso <span class="text-danger">*</span>
                            </label>
                            <el-input v-model="form.ws_api_token" show-password></el-input>
                            <small class="form-control-feedback" v-if="errors.ws_api_token" v-text="errors.ws_api_token[0]"></small>
                        </div>
                    </div>
    
                </div>
    
                <div class="form-actions text-right pt-2">
                    <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
                </div>
            </form>
        </div> 
    </div>
</template>

<script>

    export default {
        data() {
            return {
                resource: 'companies',
                recordId: null,
                form: {},
                errors: {},
                loading_submit: false,
            }
        },
        created() {
            this.initForm()
            this.getData()
        },
        methods: {
            submit(){

                this.loading_submit = true
                this.$http.post(`/${this.resource}/store-whatsapp-api`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })

            },
            initForm(){

                this.form = {
                    ws_api_token : null,
                    ws_api_phone_number_id : null,
                }

                this.errors = {}

            },
            getData() {
                this.$http.get(`/${this.resource}/record-whatsapp-api`)
                    .then(response => {
                        this.form = response.data
                    })
            }, 
        }
    }
</script>
