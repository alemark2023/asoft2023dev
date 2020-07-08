<template>
    <div class="row">
        <div class="card col-md-12">
            <div class="card-header justify-content-center d-block">
                <div class="text-center mt-2">
                    <el-button @click.prevent="start()" :loading="loading_submit">Iniciar Proceso</el-button>
                </div>
                <p class="mb-0">Espacio disponible en disco: {{discUsed}}</p>
                <p class="mb-0">Espacio ocupado por archivos de facturación: {{storageSize}}</p>
            </div>
            <div class="card-body">

                <h3>Backups</h3>

                <div v-if="db.status == 'success'">
                    <h3>Base de datos <span v-if="db.error == ''" class="text-success"><i class="fas fa-check"></i></span></h3>
                    <h4>Log: {{db.content}}</h4>
                    <span v-if="db.error ==! ''" class="text-danger">{{db.error}}</span>

                </div>

                <div v-if="files.status == 'success'">
                    <h3>Archivos <span v-if="files.error == ''" class="text-success"><i class="fas fa-check"></i></span></h3>
                    <h4>Log: {{files.content}}</h4>
                    <span class="text-danger">{{files.error}}</span><br>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    import $ from 'jquery'

    export default {
        props: ['storageSize','discUsed'],
        data() {
            return {
                headers: null,
                resource: 'backup',
                errors: {},
                form: {},
                loading_submit: false,
                db: {
                    error: '',
                    content: '',
                    status: '',
                },
                files: {
                    error: '',
                    content: '',
                    status: '',
                },
            }
        },
        created() {

        },
        methods: {
            async start() {
                this.initContent()
                this.loading_submit = true
                this.backupDb()
            },
            initContent() {
                this.db.error = ''
                this.db.content = ''
                this.db.status = false
                this.files.error = ''
                this.files.content = ''
                this.files.status = false
            },
            backupDb() {
                this.$http.get(`/${this.resource}/db`)
                .then(response => {
                    if (response.data !== '') {
                        this.db.content = response.data
                        if (response.status === 200) {
                            this.db.status = 'success'
                        }
                        this.backupFiles()
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.db.error = error.response.data.message
                        this.db.status = 'false'
                    } else {
                        console.log(error)
                    }
                })
            },
            backupFiles() {
                this.$http.get(`/${this.resource}/files`)
                .then(response => {
                    if (response.data !== '') {
                        this.files.content = response.data
                        if (response.status === 200) {
                            this.files.status = 'success'
                        }
                        this.loading_submit = false
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.files.error = error.response.data.message
                        this.files.status = 'false'
                    } else {
                        console.log(error)
                    }
                })

            },
        }
    }
</script>
