<template>
    <el-dialog :close-on-click-modal="false"
               :title="titleDialog"
               :visible="showDialog"
               :append-to-body="true"
               @close="close"
               @open="create">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.name}"
                                class="form-group">
                            <label class="control-label">Nombre <span class="text-danger">*</span></label>
                            <el-input v-model="form.name"
                                        dusk="name"></el-input>
                            <small v-if="errors.name"
                                    class="form-control-feedback"
                                    v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.description}"
                                class="form-group">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description"
                                        dusk="trade_name"></el-input>
                            <small v-if="errors.description"
                                    class="form-control-feedback"
                                    v-text="errors.description[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           native-type="submit"
                           type="primary">Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

// import {serviceNumber} from '../../../mixins/functions'

export default {
    // mixins: [serviceNumber],
    props: [
        'showDialog',
        'type',
        'recordId',
    ],
    data() {
        return {
            loading_submit: false,
            titleDialog: null,
            resource: 'salud',
            form: {},
            errors: {}
        }
    },
    async created() {

        this.loadConfiguration()
        await this.initForm()
        // await this.$http.get(`/${this.resource}/tables`)
        //     .then(response => {
        //         this.api_service_token = response.data.api_service_token
        //         // console.log(this.api_service_token)

        //         this.countries = response.data.countries
        //         this.zones = response.data.zones
        //         this.sellers = response.data.sellers
        //         this.all_departments = response.data.departments;
        //         this.all_provinces = response.data.provinces;
        //         this.all_districts = response.data.districts;
        //         this.identity_document_types = response.data.identity_document_types;
        //         this.locations = response.data.locations;
        //         this.person_types = response.data.person_types;
        //         this.discount_types = response.data.discount_types;
        //     })
        //     .finally(() => {
        //         if (this.api_service_token === false) {
        //             if (this.config.api_service_token !== undefined) {
        //                 this.api_service_token = this.config.api_service_token
        //             }
        //         }
        //     })

    },
    computed: {
        ...mapState([
            'config',
            'person',
            'parentPerson',
        ])
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                name: null,
                description: null
            }
        },
        create() {
            this.titleDialog = (this.recordId) ? 'Editar Especialidad' : 'Nueva Especialidad'

            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                    })
            }
        },
        async submit() {
            this.loading_submit = true
            this.form.parent_id = parseInt(this.parent);
            await this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataPersons', response.data.id)
                        } else {
                            this.$eventHub.$emit('reloadData')
                        }
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
                .finally(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$eventHub.$emit('initInputPerson')
            this.$emit('update:showDialog', false)
            this.initForm()
        }
    }
}
</script>
