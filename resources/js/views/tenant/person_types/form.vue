<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción <span class="text-danger">*</span></label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div> 
                </div>
                <div class="col-md-3">
                    <div :class="{'has-danger': errors.price_id}"
                            class="form-group">
                        <label class="control-label">Listado de Precios</label>
                        <el-select v-model="form.price_id"
                                    dusk="unit_type_id">
                            <el-option v-for="option in itemPriceTypes"
                                        :key="option.id"
                                        :label="option.name"
                                        :value="option.id"></el-option>
                        </el-select>
                        <small v-if="errors.price_id"
                                class="form-control-feedback"
                                v-text="errors.price_id[0]"></small>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>


    export default {
        props: ['showDialog', 'recordId','itemPriceTypes'],
        data() {
            return {
                titleDialog: null,
                loading_submit: false,
                resource: 'person-types',
                errors: {},
                form: {}, 
            }
        },
        created() {
            this.initForm() 
        }, 
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    description: null,
                    price_name: null,
                }
            },
            create() { 
                this.titleDialog = (this.recordId)? 'Editar tipo de cliente':'Nuevo tipo de cliente'

                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                        })
                }
            }, 
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message) 
                            this.$eventHub.$emit('reloadData')
                            this.close()
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
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }, 
        }
    }
</script>