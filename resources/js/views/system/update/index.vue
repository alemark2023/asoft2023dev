<template>
    <div class="row">
        <div class="card col-md-12">
            <!-- <div class="card-header bg-info">Comprobando Rama de Repositorio</div> -->
            <div class="card-body">
                <h3>Comprobando rama del repositorio</h3>
                <el-progress :percentage="branch.percent"></el-progress>
                <h4>Rama actual: <strong>{{branch.name}}</strong></h4>
                <span class="text-danger">{{branch.error}}</span><br>
                <span class="text-danger">{{branch.status}}</span>

                <div v-if="branch.status == 'success'">
                    <hr>
                    <h3>Descargando actualización</h3>
                    <h4>Resultado: {{pull.content}}</h4>
                    <span class="text-danger">{{pull.error}}</span><br>
                    <span class="text-danger">{{pull.status}}</span>
                </div>

                <div v-if="pull.content == 'Already up to date.'">
                    <hr>
                    <h3>El sistema está actualizado</h3>
                </div>
                <div v-if="pull.content != 'Already up to date.' && pull.status == 'success'">
                    <hr>
                    <h3>Comandos Artisan</h3>
                    <h4>Resultado: {{artisan.content}}</h4>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                headers: null,
                resource: 'auto-update',
                errors: {},
                form: {},
                branch: {
                    name: '',
                    percent: 1,
                    error: '',
                    status: '',
                },
                pull: {
                    error: '',
                    status: '',
                    content: '',
                },
                artisan: {
                    error: '',
                    status: '',
                    content: '',
                }
            }
        },
        async created() {
            await this.getBranch()

        },
        methods: {
            getBranch() {
                this.branch.percent = 40
                this.$http.get(`/${this.resource}/branch`)
                .then(response => {
                    this.branch.percent = 70
                    if (response.data !== '') {
                        this.branch.name = response.data
                        this.branch.percent = 100
                        if (response.status === 200) {
                            this.branch.status = 'success'
                        }
                        this.execPull()
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.branch.percent = 0
                        // this.$message.error('Error consultado rama: '+error.response.data.message)
                        this.branch.error = error.response.data.message
                        this.branch.status = 'false'
                    } else {
                        console.log(error)
                    }
                })
            },
            execPull() {
                this.$http.get(`/${this.resource}/pull/${this.branch.name}`)
                .then(response => {
                    if (response.data !== '') {
                        this.pull.content = response.data
                        this.pull.percent = 100
                        if (response.status === 200) {
                            this.pull.status = 'success'
                        }
                        this.execArtisan()
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.pull.percent = 0
                        // this.$message.error('Error consultado rama: '+error.response.data.message)
                        this.pull.error = error.response.data.message
                        this.pull.status = 'false'
                    } else {
                        console.log(error)
                    }
                })
            },
            execArtisan() {
                this.$http.get(`/${this.resource}/artisans`)
                .then(response => {
                    console.log(response)
                    if (response.data !== '') {
                        this.artisan.content = response.data
                        this.artisan.percent = 100
                        if (response.status === 200) {
                            this.artisan.status = 'success'
                        }
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.artisan.percent = 0
                        // this.$message.error('Error consultado rama: '+error.response.data.message)
                        this.artisan.error = error.response.data.message
                        this.artisan.status = 'false'
                    } else {
                        console.log(error)
                    }
                })
            }
        }
    }
</script>
