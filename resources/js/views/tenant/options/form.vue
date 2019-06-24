<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Otras Operaciones</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="deleteDocuments">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <el-button type="primary" native-type="submit" :loading="loading_submit">Eliminar documentos de prueba</el-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading_submit: false,
                resource: 'options',
                errors: {},
                form: {},
            }
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {}
            },
            deleteDocuments() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}/delete_documents`)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors
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
