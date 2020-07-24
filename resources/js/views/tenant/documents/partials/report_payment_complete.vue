<template>
    <el-dialog :title="title" :visible="showDialog" @close="closeDialog">
        <div class="">
            <div class="row mt-2">
                <div class="col-lg-4 col-md-4 pb-4">
                    <div class="form-group">
                        <label class="control-label">Seleccione Mes </label>

                        <el-date-picker
                            v-model="search.month"
                            type="month"
                            style="width: 100%;"
                            placeholder="Buscar"
                            value-format="yyyy-MM-dd"
                        >
                        </el-date-picker>
                    </div>
                </div>
                 <div class="col-lg-2 col-md-2 pb-4">
                    <div class="form-group"  style="padding: 2.5%;"> <br>

                    </div>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button type="warning" @click="closeDialog">Cancelar</el-button>
            <el-button type="primary" @click="downloadReportComplete('excel')">Descargar</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "documentId"],
    data() {
        return {
            title: "Reporte de Pagos Completo",
            resource: "documents",
            search: {},
            pickerOptionsDates: {
                disabledDate: time => {
                    time = moment(time).format("MM");
                    return this.search.month > time;
                }
            }
        };
    },
    async created() {},
    methods: {
        closeDialog() {
            this.$emit("update:showDialog", false);
        },
        downloadReportComplete(type)
        {
            if(this.search.month){

                window.open(`/${this.resource}/payments/${type}/${this.search.month}`, '_blank');

            } else {
                this.$message.error('Debe completar el mes');
            }
        }
    }
};
</script>
