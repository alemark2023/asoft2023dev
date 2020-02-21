<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuraciones</h3>
        </div>
        <div class="card-body pt-0 pb-5">
            <form autocomplete="off">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="separator-title">Servicios</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Envío de comprobantes automático</label>
                            <div class="form-group" :class="{'has-danger': errors.send_auto}">
                                <el-switch v-model="form.send_auto" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.send_auto" v-text="errors.send_auto[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="typeUser != 'integrator'">
                            <label class="control-label">Crontab <small>Tareas Programadas</small></label>
                            <div class="form-group" :class="{'has-danger': errors.cron}">
                                <el-switch v-model="form.cron" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.cron" v-text="errors.cron[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Envío de comprobantes a servidor alterno de SUNAT</label>
                            <div class="form-group" :class="{'has-danger': errors.sunat_alternate_server}">
                                <el-switch v-model="form.sunat_alternate_server" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.sunat_alternate_server" v-text="errors.sunat_alternate_server[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="separator-title">Contable</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Cantidad decimales POS</label>
                            <div class="form-group" :class="{'has-danger': errors.decimal_quantity}">
                                <el-input-number v-model="form.decimal_quantity" @change="submit" :min="2" :max="10"></el-input-number>
                                <small class="form-control-feedback" v-if="errors.decimal_quantity" v-text="errors.decimal_quantity[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Impuesto bolsa plástica</label>
                            <div class="form-group" :class="{'has-danger': errors.amount_plastic_bag_taxes}">
                                <el-input-number v-model="form.amount_plastic_bag_taxes" @change="changeAmountPlasticBagTaxes" :precision="2" :step="0.1" :max="0.5" :min="0.1"></el-input-number>
                                <small class="form-control-feedback" v-if="errors.amount_plastic_bag_taxes" v-text="errors.amount_plastic_bag_taxes[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-4" v-if="typeUser != 'integrator'"> <br>
                            <label class="control-label">Cantidad de columnas en productos</label>
                            <div class="form-group" :class="{'has-danger': errors.amount_plastic_bag_taxes}">
                                <el-slider  @change="submit" v-model="form.colums_grid_item" :min="2" :max="6"></el-slider>
                                <small class="form-control-feedback" v-if="errors.amount_plastic_bag_taxes" v-text="errors.amount_plastic_bag_taxes[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="separator-title">Visual</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Plantillas PDF</label>
                            <el-select v-model="formato.formats" @change="changeFormat(formato.formats)">
                                <el-option disabled value="">Seleccione Plantilla</el-option>
                                <el-option v-for="(option, index) in formatos" :key="index" v-bind:value="option.formats"> {{option.formats}}</el-option>
                            </el-select>
                            <small class="form-control-feedback" v-model="form.formats"> Plantilla actual: {{form.formats}}</small>
                        </div>
                        <div class="col-md-6">
                            <label>Actualizar lista de plantillas</label><br>
                            <el-button type="success" @click="addSeeder" icon="el-icon-refresh"></el-button>
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
                form: {},
                formatos: [],
                formato: {
                    formats: ''
                },
                placeholder:'',

            }
        },
        async created() {
            await this.initForm();

            await this.$http.get(`/${this.resource}/record`) .then(response => {
                if (response.data !== ''){
                this.form = response.data.data;
                this.placeholder = response.data.data.formats;
                }
                console.log(this.placeholder)
            });
             await this.$http.get(`/${this.resource}/getFormats`) .then(response => {
                if (response.data !== '') this.formatos = response.data
                console.log(this.formatos)
            });
        },
        methods: {
           addSeeder(){
            var ruta = location.host
            location.href="/configurations/addSeeder"
            },
            changeFormat(value){
               this.formato = {
                    formats: value,
               }

               this.$http.post(`/${this.resource}/changeFormat`, this.formato).then(response =>{
                   this.$message.success(response.data.message);
                    location.reload()
               })

            },
            initForm() {
                this.errors = {};
                this.form = {
                    send_auto: true,
                    formats: 'default',
                    stock: true,
                    cron: true,
                    id: null,
                    sunat_alternate_server: false,
                    subtotal_account:null,
                    compact_sidebar:true,
                    decimal_quantity: null,
                    amount_plastic_bag_taxes: 0.1,
                    colums_grid_item: 4
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
            },
            changeAmountPlasticBagTaxes() {
                this.loading_submit = true;

                this.$http.post(`/${this.resource}/icbper`, this.form).then(response => {
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
