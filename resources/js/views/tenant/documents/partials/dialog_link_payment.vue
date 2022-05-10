<template>
    <div class="pt-2">
        <el-dialog :title="titleDialog" :visible="showDialog" class="" @close="close" @open="create" >

            <el-tabs v-model="activeTab" @tab-click="handleClick" v-loading="loading">
                <el-tab-pane label="Yape" name="01" v-if="payment_configuration.enabled_yape">
                    <div class="row">
                        <div class="col-md-7 bg-yape text-center pr-0">
                            <img src="/logo/yape-logo.png" class="img-fluid" alt="Yape">
                            <div class="payment-links-yape">

                                <template v-if="has_payment_link">
                                    
                                    <el-button type="primary" class="mt-2" icon="el-icon-share" 
                                        v-clipboard:copy="form.user_payment_link"
                                        v-clipboard:success="onCopy"
                                        v-clipboard:error="onError"
                                    >
                                        Copiar Link
                                    </el-button>

                                    <el-button type="primary" class="mt-2" icon="fas fa-envelope fa-fw">Enviar Correo</el-button>
                                    <el-button type="primary" class="my-2" icon="fab fa-whatsapp fa-fw">Enviar Whatsapp</el-button>

                                </template>
                                <template v-else>
                                    <el-button type="primary" class="mt-2 mb-2" icon="el-icon-link" @click="clickGenerateLink" :loading="loading_generate_link">Generar Link</el-button>
                                </template>

                            </div>
                        </div>
                        <div class="col-md-5 pt-2">
                            <el-button type="primary">Adjuntar pago</el-button>

                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="Mercado Pago" name="02">
                    <div class="row">
                        <div class="col-md-7 bg-mercadopago text-center pr-0">
                            <img src="/logo/logo_mercadopago.jpg" class="img-fluid" alt="Yape">
                            <div class="payment-links-mp">

                                <template v-if="has_payment_link">

                                    <el-button type="primary" class="mt-2" icon="el-icon-share">Copiar Link</el-button>
                                    <el-button type="primary" class="mt-2" icon="fas fa-envelope fa-fw">Enviar Correo</el-button>
                                    <el-button type="primary" class="my-2" icon="fab fa-whatsapp fa-fw">Enviar Whatsapp</el-button>
                                
                                </template>
                                <template v-else>
                                    <el-button type="primary" class="mt-2" icon="el-icon-link" @click="clickGenerateLink" :loading="loading_generate_link">Generar Link</el-button>
                                </template>

                            </div>
                        </div>
                        <div class="col-md-5 pt-2">
                            <el-button type="primary">Consultar estado</el-button>
                        </div>
                    </div>
                </el-tab-pane>
            </el-tabs>

            <div class="row mt-3">
                <div class="col-md-12">
                    <h4 class="text-left"><b>Valor: {{payment}}</b></h4>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        props: [
            'documentPaymentId',
            'payment',
            'showDialog',
        ],
        data() {
            return {
                loading: false,
                titleDialog: 'Link de Pago',
                activeTab: '01',
                payment_configuration: {},
                titleDialog: null,
                loading_generate_link: false,
                resource: 'payment-links',
                errors: {},
                form: {},
                has_payment_link: false,
                copyTextValue: null,
            }
        },
        async created() {
            await this.initForm()
            await this.getConfiguration()
        },
        methods: {
            onCopy: function(e) {
                this.$message.success('Texto copiado al portapapeles')
            },
            onError: function(e) {
                this.$message.error('No se pudo copiar el texto al portapapeles')
                console.log(e);
            },
            handleClick(){
                this.form.payment_link_type_id = this.activeTab
                this.getData()
            },
            async create(){

                this.form.payment_id = this.documentPaymentId
                this.form.total = this.payment

                await this.getData()

            },
            clickGenerateLink(){

                this.loading_generate_link = true

                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getData()
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
                    .then(()=>{
                        this.loading_generate_link = false
                    })
            },
            initForm(){

                this.form = {
                    id: null,
                    payment_link_type_id: this.activeTab,
                    payment_id: null,
                    total: 0,
                    instance_type: 'document',
                    user_payment_link: null,
                }

                this.titleDialog = 'Link de pago'

            },
            async getData() {

                this.loading = true

                await this.$http.get(`/${this.resource}/record/${this.documentPaymentId}/${this.form.instance_type}/${this.form.payment_link_type_id}`)
                    .then(response => {

                        this.has_payment_link = response.data.has_payment_link
                        if(this.has_payment_link) this.form = response.data.data

                    })
                    .then(()=>{
                        this.loading = false
                    })

            }, 
            async getConfiguration() {
                await this.$http.get(`/payment-configurations/record`)
                    .then(response => {
                        this.payment_configuration = response.data.data
                    })
            }, 
            close() {
                this.$emit('update:showDialog', false);
            },
        }
    }
</script>


<style>
    .el-dialog__body {
        padding-top: 0px;
    }

    .payment-links-yape .el-button--primary {
        background-color: #10cbb4;
        border-color: #10cbb4;
    }

    .bg-yape {
        background: #5e186c;
    }
</style>
