<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">RUC</label>
                            <el-input v-model="form.number" :maxlength="11" dusk="number">
                                <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchSunat">
                                    SUNAT
                                </el-button>
                            </el-input>
                        </div>

                        <!--<div class="form-group" :class="{'has-danger': errors.number}">-->
                            <!--<label class="control-label">RUC</label>-->
                            <!--<el-input v-model="form.number" :maxlength="11" dusk="number"></el-input>-->
                            <!--<small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>-->
                        <!--</div>-->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre de la Empresa</label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.subdomain || errors.uuid)}">
                            <label class="control-label">Nombre de Subdominio</label>
                            <el-input v-model="form.subdomain" dusk="subdomain">
                                <template slot="append">{{ url_base }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.subdomain" v-text="errors.subdomain[0]"></small>
                            <small class="form-control-feedback" v-if="errors.uuid" v-text="errors.uuid[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo de Acceso</label>
                            <el-input v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.password)}">
                            <label class="control-label">Contraseña</label>
                            <el-input type="password" v-model="form.password" dusk="password"></el-input>
                            <small class="form-control-feedback" v-if="errors.password" v-text="errors.password[0]"></small> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.plan_id}">
                            <label class="control-label">Plan</label>
                            <el-select v-model="form.plan_id" dusk="plan_id">
                                <el-option v-for="option in plans" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.plan_id" v-text="errors.plan_id[0]"></small>
                        </div>
                    </div>                   
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.type}">
                            <label class="control-label">Perfil</label>
                            <el-select v-model="form.type">
                                <el-option v-for="option in types" :key="option.type" :value="option.type" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" dusk="submit">
                    <template v-if="loading_submit">
                        Creando base de datos...
                    </template>
                    <template v-else>
                        Guardar
                    </template>
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>


<script>

    import {serviceNumber} from '../../../mixins/functions'

    export default {
        mixins: [serviceNumber],
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                loading_search: false,
                titleDialog: null,
                resource: 'clients',
                error: {},
                form: {},
                url_base: null,
                plans:[],
                types:[],
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.url_base = response.data.url_base
                    this.plans = response.data.plans
                    this.types = response.data.types
                })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    name: null,
                    email: null,
                    identity_document_type_id: '6',
                    number: '',
                    password:null,
                    plan_id:null,
                    type:null
                }
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`${this.resource}`, this.form)
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
                        }else if(error.response.status === 500){
                            this.$message.error(error.response.data.message);
                        }
                         else {
                            console.log(error.response)
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
            searchSunat() {
                this.searchServiceNumber()
            }
        }
    }
</script>