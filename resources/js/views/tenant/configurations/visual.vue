<template>
    <div id="styleSwitcher" class="style-switcher">

        <a id="styleSwitcherOpen" class="style-switcher-open" href="#"><i class="fas fa-cogs"></i></a>

        <form class="style-switcher-wrap" autocomplete="off">

            <h4>Configuraciones visuales</h4>

            <div v-if="visual == null">
                <h5 class="">No posee ajustes actualmente</h5>
                <a href="" class="text-warning" v-if="typeUser != 'integrator'">cargar ajustes por defecto</a>
                <br>
            </div>
            <div v-if="typeUser != 'integrator'">

                <h5>Fondo oscuro</h5>
                <el-switch
                    v-model="visuals.bg"
                    active-text="Dark"
                    inactive-text="Light"
                    active-value="dark"
                    inactive-value="light"
                    active-color="#383f48"
                    inactive-color="#ccc"
                    @change="submit">
                </el-switch>

                <div class="hidden-on-dark pt-3">
                    <h5>Encabezado</h5>
                    <el-switch
                        v-model="visuals.header"
                        active-text="Dark"
                        inactive-text="Light"
                        active-value="dark"
                        inactive-value="light"
                        active-color="#383f48"
                        inactive-color="#ccc"
                        @change="submit">
                    </el-switch>
                </div>

                <div class="hidden-on-dark pt-3">
                    <h5>Paneles</h5>
                    <el-switch
                        v-model="visuals.sidebars"
                        active-text="Dark"
                        inactive-text="Light"
                        active-value="dark"
                        inactive-value="light"
                        active-color="#383f48"
                        inactive-color="#ccc"
                        @change="submit">
                    </el-switch>
                </div>

                <div class="pt-3">
                    <h5>Menú lateral contraído</h5>
                    <div :class="{'has-danger': errors.compact_sidebar}">
                        <el-switch
                            v-model="form.compact_sidebar"
                            active-text="Si"
                            inactive-text="No"
                            @change="submitForm">
                        </el-switch>
                        <br>
                        <small class="form-control-feedback" v-if="errors.compact_sidebar" v-text="errors.compact_sidebar[0]"></small>
                    </div>
                </div>

                <div class="pt-3">
                    <h5>Cantidad de columnas en POS</h5>
                    <div :class="{'has-danger': errors.amount_plastic_bag_taxes}">
                        <el-slider
                            @change="submitForm"
                            v-model="form.colums_grid_item"
                            :min="3"
                            :max="6">
                        </el-slider>
                        <small class="form-control-feedback" v-if="errors.amount_plastic_bag_taxes" v-text="errors.amount_plastic_bag_taxes[0]"></small>
                    </div>
                </div>

                <div class="pt-3">
                    <h5>Ver icono de soporte</h5>
                    <div :class="{'has-danger': errors.enable_whatsapp}">
                        <el-switch
                            v-model="form.enable_whatsapp"
                            active-text="Si"
                            inactive-text="No"
                            @change="submitForm">
                        </el-switch>
                        <small class="form-control-feedback" v-if="errors.enable_whatsapp" v-text="errors.enable_whatsapp[0]"></small>
                        <br>
                        <small class="form-control-feedback">Se mostrará si el administrador ha añadido número de soporte</small>
                    </div>
                </div>

            </div>
        </form>

    </div>
</template>

<script>
    export default {
        props:['visual','typeUser'],

        data() {
            return {
                loading_submit: false,
                resource: 'configurations',
                errors: {},
                form: {},
                visuals: {},
            }
        },
        async created() {
            await this.initForm()
            await this.getRecords()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: 1,
                    compact_sidebar: true,
                    colums_grid_item: 4,
                    enable_whatsapp: true,
                    phone_whatsapp: ''
                }
            },
            getRecords() {
                this.$http.get(`/${this.resource}/record`) .then(response => {
                    if (response.data !== ''){
                        this.visuals = response.data.data.visual;
                        this.form = response.data.data;
                    }
                });
            },
            submit() {
                this.$http.post(`/${this.resource}/visual_settings`, this.visuals).then(response => {
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
                    location.reload();
                });
            },
            submitForm() {
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
        }
    }
</script>
