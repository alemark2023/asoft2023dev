<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="create">
        <form
            autocomplete="off"
            @submit.prevent="submit">
            <el-tabs v-model="tabActive">
                <el-tab-pane class
                             name="first">
                    <span slot="label">
                        Datos del cliente
                    </span>
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    :class="{'has-danger': errors.identity_document_type_id}"
                                    class="form-group">
                                    <label class="control-label">
                                        Tipo Doc. Identidad
                                    </label>
                                    <el-select
                                        v-model="form.identity_document_type_id"
                                        filterable>
                                        <el-option
                                            v-for="option in identity_document_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.identity_document_type_id"
                                        class="form-control-feedback"
                                        v-text="errors.identity_document_type_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.number}"
                                     class="form-group">
                                    <label class="control-label">
                                        Número
                                    </label>
                                    <el-input
                                        v-model="form.number"
                                        :maxlength="maxLength">
                                        <template
                                            v-if="form.identity_document_type_id === '6' || form.identity_document_type_id === '1'">
                                            <el-button
                                                slot="append"
                                                :loading="loading_search"
                                                icon="el-icon-search"
                                                type="primary"
                                                @click.prevent="searchCustomer">
                                            </el-button>
                                        </template>
                                    </el-input>
                                    <small
                                        v-if="errors.number"
                                        class="form-control-feedback"
                                        v-text="errors.number[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    :class="{'has-danger': errors.name}"
                                    class="form-group">
                                    <label class="control-label">
                                        Nombre
                                    </label>
                                    <el-input v-model="form.name">
                                    </el-input>
                                    <small
                                        v-if="errors.name"
                                        class="form-control-feedback"
                                        v-text="errors.name[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.trade_name}"
                                     class="form-group">
                                    <label class="control-label">
                                        Nombre comercial
                                    </label>
                                    <el-input
                                        v-model="form.trade_name">
                                    </el-input>
                                    <small
                                        v-if="errors.trade_name"
                                        class="form-control-feedback"
                                        v-text="errors.trade_name[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.country_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        País
                                    </label>
                                    <el-select
                                        v-model="form.country_id"
                                        filterable>
                                        <el-option
                                            v-for="option in countries"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small v-if="errors.country_id"
                                           class="form-control-feedback"
                                           v-text="errors.country_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.department_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Departamento
                                    </label>
                                    <el-select
                                        v-model="form.department_id"
                                        filterable
                                        @change="filterProvince">
                                        <el-option
                                            v-for="option in all_departments"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.department_id"
                                        class="form-control-feedback"
                                        v-text="errors.department_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.province_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Provincia
                                    </label>
                                    <el-select
                                        v-model="form.province_id"
                                        filterable
                                        @change="filterDistrict">
                                        <el-option
                                            v-for="option in provinces"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.province_id"
                                        class="form-control-feedback"
                                        v-text="errors.province_id[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.province_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Distrito
                                    </label>
                                    <el-select
                                        v-model="form.district_id"
                                        filterable>
                                        <el-option
                                            v-for="option in districts"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small v-if="errors.district_id"
                                           class="form-control-feedback"
                                           v-text="errors.district_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div :class="{'has-danger': errors.address}"
                                     class="form-group">
                                    <label class="control-label">
                                        Dirección
                                    </label>
                                    <el-input
                                        v-model="form.address">
                                    </el-input>
                                    <small
                                        v-if="errors.address"
                                        class="form-control-feedback"
                                        v-text="errors.address[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.telephone}"
                                     class="form-group">
                                    <label class="control-label">
                                        Teléfono
                                    </label>
                                    <el-input
                                        v-model="form.telephone">
                                    </el-input>
                                    <small
                                        v-if="errors.telephone"
                                        class="form-control-feedback"
                                        v-text="errors.telephone[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.email}"
                                     class="form-group">
                                    <label class="control-label">
                                        Correo electrónico
                                    </label>
                                    <el-input
                                        v-model="form.email">
                                    </el-input>
                                    <small
                                        v-if="errors.email"
                                        class="form-control-feedback"
                                        v-text="errors.email[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </el-tab-pane>
                <el-tab-pane class
                             name="second">
                    <span slot="label">Datos de los hijos </span>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="font-weight-bold">Tipo de documento</th>
                                            <th class="font-weight-bold">Numerp</th>
                                            <th class="font-weight-bold">Nombre</th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody v-if="form.childrens !== undefined && form.childrens.length > 0">
                                        <tr v-for="(row, index) in form.childrens"
                                            :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.document_type }}</td>
                                            <td>{{ row.number }}</td>
                                            <td>{{ row.name }}</td>

                                            <td class="text-right">
                                                <button class="btn waves-effect waves-light btn-xs btn-info"
                                                        type="button"
                                                        @click="ediItem(row, index)">
                                                    <span style='font-size:10px;'>&#9998;</span>
                                                </button>
                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveItem(index)">
                                                    x
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12  text-center">
                                <div class="form-group">
                                    <button class="btn waves-effect waves-light btn-primary"
                                            type="button"
                                            @click="clickAddItem">+ Agregar Hijo
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </el-tab-pane>

            </el-tabs>
            <div class="form-actions text-right mt-4">
                <el-button
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary">
                    Guardar
                </el-button>
            </div>
        </form>

        <person-form
            :parentId="form.id"
            :recordId="recordIdChildren"
            :reload-data="getData"
            :showDialog.sync="showDialogChildren"
            :type="'customers'"
            @add="addRow"

        >

        </person-form>
    </el-dialog>

</template>

<script>

import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import PersonForm from "./person.vue"
import {serviceNumber} from '../../../../../../resources/js/mixins/functions'

export default {
    components: {
        PersonForm
    },
    mixins: [
        serviceNumber
    ],
    props: [
        'showDialog',
        'recordId',
        'external'
    ],
    data() {
        return {
            recordIdChildren: null,
            loading_submit: false,
            titleDialog: null,
            showDialogChildren: false,
            parent: null,
            errors: {},
            tabActive: 'first',
            form: {},
            countries: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            provinces: [],
            districts: [],
            identity_document_types: []
        }
    },
    created() {
        this.initForm()
        this.$http
            .post(`/suscription/${this.resource}/tables`, {})
            .then(response => {
                this.countries = response.data.countries
                this.all_departments = response.data.departments
                this.all_provinces = response.data.provinces
                this.all_districts = response.data.districts
                this.identity_document_types = response.data.identity_document_types
            })
    },
    computed: {
        ...mapState([
            'config',
            'resource',
            'person',
            'parentPerson',
        ]),
        maxLength: function () {
            if (this.form.identity_document_type_id === '066') {
                return 11
            }
            if (this.form.identity_document_type_id === '061') {
                return 8
            }
        }


    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.tabActive = 'first'
            this.errors = {}
            this.form = {
                id: null,
                identity_document_type_id: '1',
                number: null,
                name: null,
                trade_name: null,
                country_id: 'PE',
                department_id: null,
                province_id: null,
                district_id: null,
                address: null,
                telephone: null,
                email: null,
                more_address: [],
            }
            this.$store.commit('setParentPerson', this.form)

        },
        create() {
            this.form.identity_document_type_id = "1"
            this.initForm()
            this.parent = this.form.id
            this.titleDialog = (this.recordId) ? 'Editar Cliente' : 'Nuevo Cliente'
            if (this.recordId) {
                this.getData()
            }
        },
        getData() {

            this.$http
                .post(`/suscription/${this.resource}/record`, {
                    person: this.recordId,
                })
                .then(response => {
                    this.form = response.data.data
                    this.$store.commit('setParentPerson', response.data.data)
                    this.filterProvinces()
                    this.filterDistricts()
                })
        },

        submit() {
            this.loading_submit = true
            this.$http.post(`/suscription/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataCustomers', response.data.id)
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
                .then(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        searchCustomer() {
            this.searchServiceNumberByType()
        },
        clickAddItem() {
            this.$store.commit('setPerson', {})

            this.recordItem = null;
            this.showDialogChildren = true;
        },
        ediItem(row, index) {
            row.indexi = index
            this.$store.commit('setPerson', row)

            this.recordIdChildren = row.id
            this.showDialogChildren = true

        },
        clickRemoveItem(index) {
            this.form.items.splice(index, s1)

        },
        addRow(data){
            this.form.childrens.push(data)
            console.error(data)
            this.$store.commit('setPerson', {})

        }
        /*
        addRow(row) {
            /* Extraido de resources/js/views/tenant/quotations/form.vue * /
                    if (this.recordItem) {
                this.fakeForm.items[this.recordItem.indexi] = row
                this.recordItem = null

            } else {
                this.fakeForm.items.push(JSON.parse(JSON.stringify(row)));
            }
            this.$store.commit('setFormData', this.fakeForm)

            this.calculateTotal();
            this.$store.commit('setFormData', this.fakeForm)
            },
            */
}
}
</script>
