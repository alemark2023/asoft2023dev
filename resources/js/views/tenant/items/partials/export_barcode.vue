<template>
    <el-dialog :visible="showDialog"
               class="dialog-import"
               title="Codigo de barras"
               @close="close">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-12">
                        <el-select v-model="type">
                            <el-option
                                v-for="option in types"
                                :key="option.id"
                                :label="option.description"
                                :value="option.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div v-if="type === 0"
                         class="col-12">
                        <template>
                            <label class="control-label">
                                Seleccione rango de impresión
                                <el-tooltip class="item"
                                            content="Generar una cantidad muy alta puede causar lentitud en el servidor"
                                            effect="dark"
                                            placement="top-start">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-slider
                                v-model="form.range"
                                :max="max_item.data"
                                :min="1"
                                range>
                            </el-slider>
                        </template>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button :loading="loading_submit"
                               native-type="submit"
                               type="primary">Procesar
                    </el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import queryString from 'query-string'

export default {
    props: [
        'showDialog',
        'pharmacy',
    ],
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            resource: 'items',
            errors: {},
            form: {
                range: [1, 100]
            },
            max_item: 1,
            type: 0,
            fromPharmacy: false,
            types: [
                {'id': 0, 'description': 'Normal'},
                {'id': 1, 'description': 'Impresión 5cm x 2.5cm'},
            ],
        }
    },
    created() {
        if (this.pharmacy !== undefined && this.pharmacy === true) {
            this.fromPharmacy = true;
        }
        this.initForm()
    },
    methods: {
        initForm() {
            this.lastItem()
            this.errors = {}
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        submit() {
            this.loading_submit = true

            let query = queryString.stringify({
                isPharmacy: this.fromPharmacy,
                ...this.form.range
            });
            if (this.type == 1) {
                window.open(`/${this.resource}/export/barcode_full/?${query}`, '_blank');
            } else {
                window.open(`/${this.resource}/export/barcode/?${query}`, '_blank');
            }
            this.loading_submit = false
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        lastItem() {
            this.$http.get(`${this.resource}/export/barcode/last`)
                .then(response => {
                    this.max_item = response.data
                })
        }
    }
}
</script>
