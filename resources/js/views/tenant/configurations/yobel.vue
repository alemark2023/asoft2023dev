<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Avanzado</span></li>
            </ol>
        </div>
        <div class="card card-dashboard border">
            <div class="card-body">
                <template>
                    <div v-if="canEdit">
                        <el-tabs v-model="activeName">
                            <el-tab-pane class="mb-3"
                                         name="first">
                                <span slot="label">
                                    <h3>Credenciales de YobelSCM</h3>
                                </span>
                                <div class="row">

                                    <div class="col-md-3 col-lg-1 mt-4">
                                        <label class="control-label">
                                            Codigo Compañia
                                        </label>
                                        <div :class="{'has-danger': errors.compania}"
                                             class="form-group">
                                            <el-input v-model="form.compania"  maxlength="3"></el-input>
                                            <small v-if="errors.compania"
                                                   class="form-control-feedback"
                                                   v-text="errors.compania[0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <label class="control-label">Usuario</label>
                                        <div :class="{'has-danger': errors.usuario}"
                                             class="form-group">
                                            <el-input v-model="form.usuario"   maxlength="10"></el-input>
                                            <small v-if="errors.usuario"
                                                   class="form-control-feedback"
                                                   v-text="errors.usuario[0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <label class="control-label">Contraseña</label>
                                        <div :class="{'has-danger': errors.password}"
                                             class="form-group">
                                            <el-input v-model="form.password" maxlength="10"></el-input>
                                            <small v-if="errors.password"
                                                   class="form-control-feedback"
                                                   v-text="errors.password[0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <label class="control-label">
                                            Activo
                                        </label>
                                        <div class="form-group" :class="{'has-danger': errors.is_active}">
                                            <el-switch v-model="form.is_active"
                                                       active-text="Si"
                                                       inactive-text="No"
                                            >

                                            </el-switch>
                                            <small class="form-control-feedback"
                                                   v-if="errors.is_active"
                                                   v-text="errors.is_active[0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <el-button
                                            class="pull-right"
                                            @click="submit">Guardar
                                        </el-button>

                                    </div>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    props: [
        'compania',
        'usuario',
        'password',
        'configuration',
        'typeUser',
    ],
    data() {
        return {
            loading_submit: false,
            resource: 'configurations/yobel',
            errors: {},
            form: {
                compania: '',
                is_active: false,
                usuario: '',
                password: '',
            },
            activeName: 'first',
        }
    },

    computed: {
        ...mapState([
            'config',
        ]),
    },
    created() {
        this.$store.commit('setConfiguration', this.configuration);
        this.loadConfiguration()
        this.form.compania = this.compania;
        this.form.usuario = this.usuario;
        this.form.password = this.password;
        this.form.is_active = this.is_active;
        this.form.send_data_to_other_server = this.config.send_data_to_other_server;
    },

    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),

        canEdit: function () {
            return this.typeUser === 'admin';

        },
        submit() {
            this.loading_submit = true;

            this.$http.post(`/${this.resource}`, this.form).then(response => {
                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.form = response.data;
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            }).then(() => {
                this.loading_submit = false;
            });
        },
    }
}
</script>

