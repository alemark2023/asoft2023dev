<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                            <label class="control-label">Tipo de comprobante</label>
                            <el-select v-model="form.document_type_id" @change="changeDocumentType">
                                <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                            <label class="control-label">Fecha de emisión</label>
                            <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group" :class="{'has-danger': errors.date_of_perception}">
                            <label class="control-label">Fecha de percepción</label>
                            <el-date-picker v-model="form.date_of_perception" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_perception" v-text="errors.date_of_perception[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.total_document}">
                            <label class="control-label">Total documento</label>
                            <el-input v-model="form.total_document"></el-input>
                            <small class="form-control-feedback" v-if="errors.total_document" v-text="errors.total_document[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.total_perception}">
                            <label class="control-label">Total percepción</label>
                            <el-input v-model="form.total_perception"></el-input>
                            <small class="form-control-feedback" v-if="errors.total_perception" v-text="errors.total_perception[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.total}">
                            <label class="control-label">Total</label>
                            <el-input v-model="form.total"></el-input>
                            <small class="form-control-feedback" v-if="errors.total" v-text="errors.total[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.exchange}">
                            <label class="control-label">Cambio</label>
                            <el-input v-model="form.exchange"></el-input>
                            <small class="form-control-feedback" v-if="errors.exchange" v-text="errors.exchange[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.payments}">
                            <label class="control-label">Pagos</label>
                            <el-input v-model="form.payments"></el-input>
                            <small class="form-control-feedback" v-if="errors.payments" v-text="errors.payments[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" native-type="submit">Agregar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog'],
        data() {
            return {
                titleDialog: '',
                resource: 'perceptions',
                errors: {},
                form: {},
                currency_types: [],
                document_types: [],
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.currency_types = response.data.currency_types
                this.document_types = response.data.document_types
            })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    document_type_id: null,
                    number: '1',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    date_of_perception: moment().format('YYYY-MM-DD'),
                    currency_type_id: null,
                    total_document: 0,
                    total_perception: 0,
                    total: 0,
                    exchange: 0,
                    payments: '',
                }
            },
            create() {
                this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            clickAddItem() {
                this.$emit('add', this.form)
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            changeDocumentType() {
                this.form.group_id = (this.form.document_type_id === '01000001')?'01':'02'
            },
            changeCurrencyType() {
                this.currency_symbol = (this.form.currency_type_code === 'PEN')?'S/':'$'
            },
        }
    }

</script>