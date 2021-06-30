<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="title"
        :visible="visible"
        @close="onClose"
        @open="onOpened"
    >
        <form autocomplete="off" @submit.prevent="onSubmit">
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>Etapa</label>
                        <el-select v-model="form.documentary_office_id">
                            <el-option
                                v-for="item in offices"
                                :key="item.id"
                                :label="item.print_name"
                                :value="item.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="form-group col-6">
                        <label>Acción</label>
                        <el-select v-model="form.documentary_action_id">
                            <el-option
                                v-for="item in actions"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="form-group col-12">
                        <label>Observación</label>
                        <el-input v-model="form.observation" type="textarea"></el-input>
                        <div v-if="errors!== undefined && errors.observation !== undefined && errors.observation.length > 0" class="invalid-feedback">
                            {{ errors.observation }}
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <el-button type="primary" @click.prevent="NextStep">Avanzar</el-button>
                        <el-button type="danger"  @click.prevent="BackStep">Devolver</el-button>
                        <!-- <el-button native-type="submit" type="primary">Guardar</el-button> -->
                        <el-button @click="onClose">Cerrar</el-button>
                    </div>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {mapState} from "vuex";

export default {
    props: {
        visible: {
            type: Boolean,
            required: true,
        },
        hasError:function(){
            if(this.form !== undefined) {
                if (this.form.observation !== undefined && this.form.observation.length === 0) return true;
                // if (this.form.documentary_office_id === 0) return true;
            }

            return false;
        },
        errors:function(){
            let observacion = '';
            let documentary_office_id = '';
            if(this.form !== undefined) {
                if (this.form.observation !== undefined && this.form.observation.length === 0) observacion = 'Debes colocar una observacion';
                // if (this.form.documentary_office_id === 0) observacion = 'Debe seleccionar una etapa';
            }
            return {
                observation:observacion,
                // documentary_office_id:documentary_office_id,
            }

        },/*
        file: {
            type: Object,
            required: false,
        },*/
    },
    data() {
        return {
            // form: {},
            loading: false,
            title: "",
        };
    },
    computed: {
        ...mapState([
            'file',
            'actions',
            'offices',
        ]),
        form: function () {
            return this.file
        }

    },
    created() {
    },
    mounted() {


        this.loading = true;
        this.$http
            .get("/documentary-procedure/files/tables")
            .then((response) => {
                const data = response.data.data;
                this.$store.commit('setActions', data.actions)
                this.$store.commit('setOffices', data.offices)

            })
            .catch((error) => this.axiosError(error))
            .finally(() => (this.loading = false));
    },
    methods: {
        NextStep() {
            if (this.hasError) {

                return null;
            } else {
                this.loading = true;
                this.$http
                    .post(
                        `/documentary-procedure/files/next`,
                        this.form
                    )
                    .then((response) => {
                        this.$message({
                            message: response.data.message,
                            type: "success",
                        });
                        this.$store.commit('setFiles', response.data.files)
                        this.$emit("onNextStep", response.data.data);
                        this.onClose();
                    });
            }


        },
        BackStep() {

            if (this.hasError) {

                return null;
            } else {
                this.loading = true;
                this.$http
                    .post(
                        `/documentary-procedure/files/back`,
                        this.form
                    )
                    .then((response) => {
                        this.$message({
                            message: response.data.message,
                            type: "success",
                        });
                        this.$store.commit('setFiles', response.data.files)
                        this.$emit("onNextStep", response.data.data);
                        this.onClose();
                    });
            }

        },
        onSubmit() {
            this.loading = true;
            this.$http
                .post(
                    `/documentary-procedure/files/${this.file.id}/add-office`,
                    this.form
                )
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$store.commit('setFiles', response.data.files)
                    this.$emit("onNextStep", response.data.data);
                    this.onClose();
                });
        },
        onOpened() {
            this.title = `Derivar expediente: ${this.file.subject}`;
        },
        onClose() {
            this.$emit("update:visible", false);
        },
    },
};
</script>
