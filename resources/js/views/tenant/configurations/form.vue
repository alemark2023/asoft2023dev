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
                            <label class="control-label">Crontab</small></label>
                            <div class="form-group" :class="{'has-danger': errors.cron}">
                                <el-switch v-model="form.cron" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.cron" v-text="errors.cron[0]"></small>
                            </div>
                        </div>
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
                    id: null
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
            }
        }
    }
</script>
