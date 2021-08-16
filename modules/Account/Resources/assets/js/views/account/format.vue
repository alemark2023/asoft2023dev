<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
        </div>

        <div v-loading="loading" class="card mb-0 pt-2 pt-md-0">
            <div class="card-header bg-info">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Periodo</label>
                        <el-date-picker
                            v-model="form.month"
                            :clearable="false"
                            format="MM/yyyy"
                            type="month"
                            value-format="yyyy-MM"></el-date-picker>
                    </div> 
                    <div class="col-md-3">
                        <label>Tipo</label>
                        <el-select v-model="form.type">
                            <el-option
                                key="sale"
                                label="Venta"
                                value="sale"></el-option>
                            <el-option
                                key="purchase"
                                label="Compra"
                                value="purchase"></el-option>
                        </el-select>
                    </div>
                </div>
                <div class="form-actions text-right pt-2">
                    <el-button :loading="loading_submit"
                            type="primary"
                            @click.prevent="clickDownload">
                        <template v-if="loading_submit">
                            Generando...
                        </template>
                        <template v-else>
                            Generar
                        </template>
                    </el-button>
                </div>
            </div>
            <!--</div>-->
        </div>

        <!-- PLE -->
        <div v-loading="loading" class="card mb-0 pt-2 pt-md-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Exportar formatos - Programa de libros electrónicos - PLE</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Periodo</label>
                        <el-date-picker
                            v-model="form_ple.month"
                            :clearable="false"
                            format="MM/yyyy"
                            type="month"
                            value-format="yyyy-MM"></el-date-picker>
                    </div> 
                    <div class="col-md-3">
                        <label>Tipo</label>
                        <el-select v-model="form_ple.type">
                            <el-option
                                key="sale"
                                label="Venta"
                                value="sale"></el-option>
                            <el-option
                                key="purchase"
                                label="Compra"
                                value="purchase"></el-option>
                        </el-select>
                    </div>
                </div>
                <div class="form-actions text-right pt-2">
                    <el-button
                            type="primary"
                            @click.prevent="clickDownloadPle"> 
                            Exportar
                    </el-button>
                </div>
            </div>
            <!--</div>-->
        </div>
    </div>
</template>

<script>
    import queryString from 'query-string'
    import {mapActions, mapState} from "vuex";

    export default {

        props: [
            'currencies',
            'configuration',

        ],
        computed: {
            ...mapState([
                'config',
                'currencys'
            ])
        },
        data() {
            return {
                loading: false,
                loading_submit: false,
                title: null,
                resource: 'account',
                error: {},
                form: {},
                form_ple: {},
            }
        },
        created() {
            this.title = 'Exportar reporte ventas - compras';
            this.$store.commit('setConfiguration', this.configuration);
            this.loadConfiguration()
        },
        mounted() {
            this.initForm();
            this.initFormPle()
        },
        methods: {
            ...mapActions([
                'loadConfiguration',
            ]),
            initForm() {
                this.errors = {};
                this.form = {
                    month: moment().format('YYYY-MM'),
                    type: 'sale'
                }
            },
            initFormPle() {
                this.form_ple = {
                    month: moment().format('YYYY-MM'),
                    type: 'sale'
                }
            },
            clickDownload() {
                this.loading_submit = true;
                let query = queryString.stringify({
                    ...this.form
                });
                window.open(`/${this.resource}/format/download?${query}`, '_blank');
                this.loading_submit = false;
            },
            clickDownloadPle() {
                // this.loading_submit = true;
                let query = queryString.stringify({
                    ...this.form_ple
                });
                window.open(`/${this.resource}/format-ple/download?${query}`, '_blank');
                // this.loading_submit = false;
            }
        }
    }
</script>
