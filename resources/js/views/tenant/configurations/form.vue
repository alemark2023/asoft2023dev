<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuraciones</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Reenvio de Facturas automático</label>
                            <div class="form-group" :class="{'has-danger': errors.send_auto}">
                                <el-switch v-model="form.send_auto" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.send_auto" v-text="errors.send_auto[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="typeUser != 'integrator'">
                            <label class="control-label">Crontab</label>
                            <div class="form-group" :class="{'has-danger': errors.cron}">
                                <el-switch v-model="form.cron" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.cron" v-text="errors.cron[0]"></small>
                            </div>
                        </div>                        
                        <div class="col-md-6 mt-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Envío de comprobantes a servidor alterno de SUNAT</label>
                            <div class="form-group" :class="{'has-danger': errors.sunat_alternate_server}">
                                <el-switch v-model="form.sunat_alternate_server" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.sunat_alternate_server" v-text="errors.sunat_alternate_server[0]"></small>
                            </div>
                        </div>
                 
                        <div class="col-md-6 mt-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Menú lateral contraído</label>
                            <div class="form-group" :class="{'has-danger': errors.compact_sidebar}">
                                <el-switch v-model="form.compact_sidebar" active-text="Si" inactive-text="No" @change="compactSidebar"></el-switch>
                                <small class="form-control-feedback" v-if="errors.compact_sidebar" v-text="errors.compact_sidebar[0]"></small>
                            </div>
                        </div>
                        <!-- <div class="col-md-6 mt-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Cuenta contable venta subtotal</label>
                            <div class="form-group" :class="{'has-danger': errors.subtotal_account}">
                                <el-input v-model="form.subtotal_account" width="50%"></el-input>                                
                                <small class="form-control-feedback" v-if="errors.subtotal_account" v-text="errors.subtotal_account[0]"></small>
                            </div>
                        </div> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props:['typeUser'],

        data() {
            return {
                loading_submit: false,
                resource: 'configurations',
                errors: {},
                form: {}
            }
        },
        async created() {
            await this.initForm();
            
            await this.$http.get(`/${this.resource}/record`) .then(response => {
                if (response.data !== '') this.form = response.data.data;
            });
        },
        methods: {
            initForm() {
                this.errors = {};
                
                this.form = {
                    send_auto: true,
                    stock: true,
                    cron: true,
                    id: null,
                    sunat_alternate_server: false,
                    subtotal_account:null,
                    compact_sidebar:true
                };
            },
            submit() {
                this.loading_submit = true;
                
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            compactSidebar() {
                this.loading_submit = true;
                
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        location.reload()
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            }
        }
    }
</script>
