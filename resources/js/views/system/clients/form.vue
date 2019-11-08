<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">RUC</label>
                            <el-input :disabled="form.is_update" v-model="form.number" :maxlength="11" dusk="number">
                                <el-button :disabled="form.is_update" type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchSunat">
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
                            <el-input :disabled="form.is_update" v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div v-if="form.is_update" class="form-group" :class="{'has-danger': (errors.subdomain || errors.uuid)}">
                            <label class="control-label">Nombre de Subdominio</label>
                            <el-input :disabled="form.is_update" v-model="form.hostname" dusk="name"></el-input>
                        </div>
                        <div v-else class="form-group" :class="{'has-danger': (errors.subdomain || errors.uuid)}">
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
                            <el-input :disabled="form.is_update" v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" v-if="!form.is_update">
                        <div class="form-group" :class="{'has-danger': (errors.password)}">
                            <label class="control-label">Contraseña</label>
                            <el-input type="password" :disabled="form.is_update" v-model="form.password" dusk="password"></el-input>
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
                        <div v-if="!form.is_update" class="form-group" :class="{'has-danger': errors.type}">
                            <label class="control-label">Perfil</label>
                            <el-select :disabled="form.is_update" v-model="form.type">
                                <el-option v-for="option in types" :key="option.type" :value="option.type" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6 center-el-checkbox mt-4">
                        <div class="form-group" :class="{'has-danger': errors.locked_emission}">
                            <el-checkbox :disabled="form.is_update" v-model="form.locked_emission">Limitar emisión de documentos</el-checkbox><br>
                            <small class="form-control-feedback" v-if="errors.locked_emission" v-text="errors.locked_emission[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" v-if="!form.is_update"> 
                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="control-label">Módulos</label>
                            <div class="row">
                                <div class="col-4" v-for="(module,ind) in form.modules" :key="ind">
                                    <el-checkbox v-model="module.checked">{{ module.description }}</el-checkbox>
                                </div>
                            </div>
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
                errors: {},
                form: {},
                url_base: null,
                plans:[],
                modules: [],
                types:[],
            }
        },
        async created() {
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.url_base = response.data.url_base
                    this.plans = response.data.plans
                    this.modules = response.data.modules
                    this.types = response.data.types
                })

            await this.initForm()

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
                    locked_emission:false,
                    type:null,
                    is_update:false,
                    modules: []
                }

                this.modules.forEach(module => {
                    this.form.modules.push({
                        id: module.id,
                        description: module.description,
                        checked: true
                    })
                })
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                                this.form = response.data.data
                                this.form.is_update = true
                            })
                }
            },
            hasModules(){

                let modules_checked = 0
                this.form.modules.forEach(module =>{
                    if(module.checked){
                        modules_checked++
                    }
                })

                return (modules_checked > 0) ? true:false

            },
            async submit() {
                // console.log(this.form)
                if(!this.form.is_update){
                    let has_modules = await this.hasModules()
                    if(!has_modules)
                        return this.$message.error('Debe seleccionar al menos un módulo')
                }
                
                
                this.loading_submit = true
                await this.$http.post(`${this.resource}${(this.form.is_update ? '/update' : '')}`, this.form)
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
