<template>
    <div>
        <el-dialog
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :title="title"
            :visible="show"
            @close="onClose"
            @open="getRecordGuide"
        >
            <!--
            <template v-if="!is_client">
                        </template>

            -->
            <div class="form-group">
                <label class="control-label">
                    Guías
                </label>
                <table style="width: 100%">
                    <tr v-for="(guide,index) in form.guides">
                        <td>
                            <el-select v-model="guide.document_type_id">
                                <el-option v-for="option in document_types_guide"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>
                        </td>
                        <td>
                            <el-input v-model="guide.number"></el-input>
                        </td>
                        <td align="right">
                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                    type="button"
                                    @click.prevent="clickRemoveGuide(index)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label class="control-label" v-if="!loading">
                                <a class=""
                                   href="#"
                                   @click.prevent="clickAddGuide"><i class="fa fa-plus font-weight-bold text-info"></i>
                                    <span style="color: #777777">Agregar guía</span></a>

                            </label>
                        </td>
                    </tr>
                </table>
            </div>


            <div class="text-center">
                <el-button
                    v-if="form.guides.length > 0"
                    :disabled="loading"
                    type="primary"
                    @click="saveGuides"
                >Guardar Guia
                </el-button
                >
                <el-button
                    :disabled="loading"
                    @click="onClose"
                >Cerrar
                </el-button>
            </div>

        </el-dialog>
    </div>
</template>
<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: {
        'establishment': {
            required: false,
            default: ''
        },
        id: {
            required: false,
            type: Number,
            default: 0

        },
        show: {
            required: false,
            type: Boolean,
            default: false

        },
        type: {
            required: false,
            type: String,
            default: ''

        },

    },
    computed: {
        ...mapState([
            'config',
            'document_types_guide',
        ]),
    },
    data() {
        return {
            // document_types_guide: [],
            loading: true,
            title: '',
            form: {
                guides: [{}],
                number: '',
                document_type_description: '',
            }

        }
    },
    created() {
        this.loadConfiguration()

    },
    methods: {

        ...mapActions([
            'loadConfiguration',
            'loadDocumentTypesGuide',
        ]),
        getRecordGuide() {
            this.form.guides = [];
            this.loading = true;
            this.$http.post(`/${this.type}/guide/${this.id}`)
                .then((result) => {
                    this.form = result.data
                    this.title = 'Guia para Documento: ' + this.form.number + "";
                    if (this.form.guides === undefined) {
                        this.form.guides = [];
                    }
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        clickAddInitGuides() {
            this.form.guides.push({
                document_type_id: '09',
                number: null
            }, {
                document_type_id: '31',
                number: null
            })
        },
        clickRemoveGuide(index) {
            this.form.guides.splice(index, 1)
        },
        saveGuides() {
            this.loading = true;
                this.form.updateGuide = 1;
            this.$http.post(`/${this.type}/guide/${this.id}`, this.form)
                .then((result) => {
                    this.onClose()

                })
                .finally(() => {
                    this.loading = false;
                })
        },
        clickAddGuide() {
            this.form.guides.push({
                document_type_id: null,
                number: null
            })
        },
        onClose() {
            this.title = 'Guias';
            this.loading = false;
            this.form = {};
            this.form.guides = [];
            this.$emit("update:show", false);

        }
    }
}
</script>
