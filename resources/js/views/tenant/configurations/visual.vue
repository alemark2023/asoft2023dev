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
                    v-model="form.bg"
                    active-text="Dark"
                    inactive-text="Light"
                    active-value="dark"
                    inactive-value="light"
                    active-color="#383f48"
                    inactive-color="#ccc"
                    @change="submit">
                </el-switch>

                <div class="hidden-on-dark">

                    <h5>Encabezado</h5>
                    <el-switch
                        v-model="form.header"
                        active-text="Dark"
                        inactive-text="Light"
                        active-value="dark"
                        inactive-value="light"
                        active-color="#383f48"
                        inactive-color="#ccc"
                        @change="submit">
                    </el-switch>

                </div>

                <div class="hidden-on-dark">

                    <h5>Paneles</h5>
                        <el-switch
                        v-model="form.sidebars"
                        active-text="Dark"
                        inactive-text="Light"
                        active-value="dark"
                        inactive-value="light"
                        active-color="#383f48"
                        inactive-color="#ccc"
                        @change="submit">
                    </el-switch>

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
                resource: 'configurations',
                errors: {},
                form: {},
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/record`) .then(response => {
                if (response.data !== ''){
                    this.form = response.data.data.visual;
                }
            });
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: 1
                }
            },
            submit() {
                this.$http.post(`/${this.resource}/visual_settings`, this.form).then(response => {
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
        }
    }
</script>
