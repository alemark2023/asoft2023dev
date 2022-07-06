<template>
    <div v-loading="loading">
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span> App 2.0</span></li>
                <li class="active"><span> Configuración</span></li>
            </ol>
            <div class="right-wrapper pull-right"></div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h4>Configuración gráfica</h4>
                        <el-form ref="form" :model="form" label-width="145px" size="mini">
                            <el-form-item label="Color de tema" class="mb-0">
                                <el-radio-group class="pt-1" v-model="form.theme_color" @change="changeThemePrimary()">
                                    <el-radio label="blue">Azul</el-radio>
                                    <el-radio label="red">Rojo</el-radio>
                                    <el-radio label="dark">Oscuro</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item label="Color de cajas" class="mb-0">
                                <el-radio-group class="pt-1" v-model="form.card_color" @change="changeThemeCards()">
                                    <el-radio label="multicolored">Multicolor</el-radio>
                                    <el-radio label="unicolor">Unicolor</el-radio>
                                </el-radio-group>
                            </el-form-item>

                            <!-- no funcionales desde aqui -->
                            <!-- <el-form-item label="Tipo de operación" class="mb-0">
                                <el-radio-group class="pt-1" v-model="form.operation_type">
                                    <el-radio :label="1">Facturación</el-radio>
                                    <el-radio :label="2">POS</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item label="Encabezado" class="mb-0">
                                <el-radio-group class="pt-1" v-model="form.header">
                                    <el-radio :label="1">Plano</el-radio>
                                    <el-radio :label="2">Ondulado</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item label="Permisos" class="mb-0">
                            </el-form-item> -->
                            <div class="form-actions text-right mt-4">
                                <el-button type="primary" @click.prevent="submit" :loading="loading_submit">Guardar</el-button>
                            </div>
                        </el-form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <iframe :src="path_app" frameborder="0" height="600" ref="appIframe" style="z-index: 999;"></iframe>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>

<script>
    export default {
        data() {
            return {
                form: {},
                domain: window.location.origin,
                path_app: window.location.origin + '/live-app',
                loading_submit: false,
                resource: 'app-configurations',
                loading: false,
            }
        },
        async created(){
            await this.initForm()
            await this.getRecord()
        },
        mounted(){
            this.checkConfiguration()
        },
        methods: {
            async getRecord(){
                
                this.loading = true 

                await this.$http.get(`/${this.resource}/record`)
                        .then(response => {
                            this.form = response.data.data
                        })
                        .then(()=>{
                            this.loading = false 
                        })


            },
            initForm(){
                
                this.form = {
                    theme_color: 'blue',
                    card_color: 'multicolored',
                    // operation_type: 1,
                    // permissions: {},
                    // header: 1,
                }

            },
            changeThemePrimary() {

                let iframe = this.$refs.appIframe
                let doc = iframe.contentDocument

                switch (this.form.theme_color) {
                    case 'red':
                        doc.head.querySelectorAll('[href="' + this.domain + '/liveapp/assets/skin-dark.css"]').forEach(el => el.remove())
                        doc.head.innerHTML = doc.head.innerHTML + '<link href="' + this.domain + '/liveapp/assets/skin-red.css" rel="stylesheet">'
                        break
                    case 'dark':
                        doc.head.querySelectorAll('[href="' + this.domain + '/liveapp/assets/skin-red.css"]').forEach(el => el.remove())
                        doc.head.innerHTML = doc.head.innerHTML + '<link href="' + this.domain + '/liveapp/assets/skin-dark.css" rel="stylesheet">'
                        break
                    default:
                        doc.head.querySelectorAll('[href="' + this.domain + '/liveapp/assets/skin-dark.css"]').forEach(el => el.remove())
                        doc.head.querySelectorAll('[href="' + this.domain + '/liveapp/assets/skin-red.css"]').forEach(el => el.remove())
                        break
                }

            },
            changeThemeCards() {
                let iframe = this.$refs.appIframe
                let doc = iframe.contentDocument

                switch (this.form.card_color) {
                    case 'unicolor':
                        doc.head.innerHTML = doc.head.innerHTML + '<link href="' + this.domain + '/liveapp/assets/cards.css" rel="stylesheet">'
                        break
                    default:
                        doc.head.querySelectorAll('[href="' + this.domain + '/liveapp/assets/cards.css"]').forEach(el => el.remove())
                        break
                }
            },
            async checkConfiguration(){

                const iframe = this.$refs.appIframe
                const context = this

                await iframe.addEventListener('load', function() {
                    context.changeThemePrimary()
                    context.changeThemeCards()
                })

            },
            async submit() {

                this.loading_submit = true
                await this.$http.post(`/${this.resource}`, this.form)
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
        }
    }
</script>
