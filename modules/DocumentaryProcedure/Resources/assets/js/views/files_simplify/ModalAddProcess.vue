<template>
    <div>
        <el-dialog
            :title="title"
            :visible="visible"
            @close="onClose"
            @open="onCreate"
        >

            <div class="form-body row">

                <div
                    :class="{ 'has-danger': errors.guide }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Número de seguimiento
                        <span class="text-danger">*</span></label>
                    <el-input v-model="guide.guide"
                              placeholder="Número de seguimiento"></el-input>
                    <small
                        v-if="errors.guide"
                        class="form-control-feedback"
                        v-text="errors.guide[0]"
                    ></small>
                </div>
                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Fecha/Hora de registro
                        <span class="text-danger">*</span></label>
                    <el-date-picker
                        v-model="guide.created_at "
                        format="yyyy/MM/dd HH:mm"
                        placeholder="Fecha/Hora de registro"
                        type="datetime"
                        value-format="yyyy-MM-dd HH:mm"
                    >
                    </el-date-picker>
                    <small
                        v-if="errors.created_at"
                        class="form-control-feedback"
                        v-text="errors.created_at[0]"
                    ></small>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Etapa
                        <span class="text-danger">*</span></label>

                    <el-select
                        v-model="guide.doc_office_id"
                        clearable
                        placeholder="Etapa"
                    >
                        <el-option
                            v-for="of in offices"
                            :key="of.id"
                            :label="of.name"
                            :value="of.id"
                        ></el-option>
                    </el-select>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Fecha cuando se toma el tramite
                        <span class="text-danger">*</span></label>
                    <el-date-picker
                        v-model="guide.date_take "
                        format="yyyy/MM/dd HH:mm"
                        placeholder="Fecha cuando se toma el tramite"
                        type="datetime"
                        value-format="yyyy-MM-dd HH:mm"
                    >
                    </el-date-picker>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Fecha de finalizacion
                        <span class="text-danger">*</span></label>
                    <el-date-picker
                        v-model="guide.date_end "
                        format="yyyy/MM/dd HH:mm"
                        placeholder="Fecha de finalizacion"
                        type="datetime"
                        value-format="yyyy-MM-dd HH:mm"
                    >
                    </el-date-picker>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Estado de tramite
                        <span class="text-danger">*</span></label>
                    <el-select
                        v-model="guide.documentary_guides_number_status_id"
                        clearable
                        placeholder="Estado de tramite"
                    >
                        <el-option
                            v-for="of in statusDocumentary"
                            :key="of.id"
                            :label="of.name"
                            :value="of.id"
                        ></el-option>
                    </el-select>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Responsable
                        <span class="text-danger">*</span></label>
                    <el-select v-model="guide.user_id"
                               clearable
                               filterable
                               placeholder="Responsable"
                               popper-class="el-select-customers">
                        <el-option v-for="option in sellers"
                                   :key="option.id"
                                   :label="option.name"
                                   :value="option.id">
                        </el-option>
                    </el-select>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Observaciones
                        <span class="text-danger">*</span></label>
                    <el-input v-model="guide.observation"
                              placeholder="Observaciones"></el-input>
                </div>
                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Archivo
                        <span class="text-danger">*</span></label>
                    <el-upload
                        :action="`/finances/payment-file/upload`"
                        :data="{'index': index}"
                        :file-list="fileList"
                        :headers="headers"
                        :limit="1"
                        :multiple="false"
                        :on-remove="handleRemove"
                        :on-success="onSuccess"
                        :show-file-list="true"
                    >
                        <el-button slot="trigger"
                                   type="primary"><i class="fas fa-file-upload"></i></el-button>
                    </el-upload>
                </div>


                <div class="row text-center col-12 p-t-20">
                    <div
                        class="col-6">
                        <el-button
                            :loading="loading"
                            class="btn-block"
                            native-type="submit"
                            type="primary"
                            @click="addRow"
                        >Guardar
                        </el-button
                        >
                    </div>
                    <div class="col-6">
                        <el-button class="btn-block"
                                   @click="onClose">Cancelar
                        </el-button>
                    </div>
                </div>
            </div>

        </el-dialog>
    </div>
</template>

<script>
import moment from "moment";
import {mapActions, mapState} from "vuex";

export default {
    components: {},
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
        guides: {
            type: Object,
            required: false,
            default: {},
        },
    },
    data() {
        return {
            headers: headers_token,
            dropzoneOptions: {
                url: 'https://httpbin.org/post',
                headers: {
                    "X-Requested-With": "X-Requested-With",
                    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content,
                },
                autoProcessQueue: false,
                paramName: function (n) {
                    return "file[]";
                },
                dictDefaultMessage: "Arrastra y suelta los archivos aqui.",
                parallelUploads: 10,
                maxFiles: 10,
                uploadMultiple: true,
            },
            tabActive: "first",
            tempAttachments: [],
            fileList: [],
            current_files: [],
            attachments: [],
            input_person: null,
            index: 0,
            urlDropzone: null,
            guide: {
                id: null,
                guide: null,
                created_at: moment().format('YYYY-MM-DD'),

                doc_office_id: null,
                date_take: null,
                date_end: null,
                documentary_guides_number_status_id: null,
                user_id: null,
                observation: null,
            },
            title: "",
            loading: false,
            basePath: "/documentary-procedure/files",
            showDialogNewPerson: false,
            errors: {},
            nextId: "",
            currentYear: 0,
            attachFile: null,
            filename: "",
            fileCount: 0,
            requirements_in_process: {
                requirements: [],
            }
        };
    },
    created() {
    },
    mounted() {
        this.onInitializeForm();
    },
    computed: {
        ...mapState([
            'offices',
            'files',
            'file',
            'actions',
            'documentTypes',
            'processes',
            'customers',
            'statusDocumentary',
            'sellers',

        ]),

    },
    methods: {
        ...mapActions([
            'loadOffices',
            'loadActions',
            'loadCustomers',
            'loadProcesses',
            'loadDocumentTypes',
            'loadFiles',
        ]),
        convertRequirementsIntoArray(val) {
            return this.guide.requirements_id;

        },
        setRequirement() {
            this.requirements_in_process = [];
            if (
                (this.guide !== undefined || this.guide !== null) &&
                (this.guide.documentary_process_id !== undefined) &&
                (this.guide.documentary_process_id !== null)
            ) {

                let temp = this.processes.find((it) => {
                    return it.id === this.guide.documentary_process_id
                });
                if (temp.requirements !== undefined) {
                    if (temp.requirements !== null) {
                        let wo = this.guide.requirements_id;

                        if (wo === undefined || wo == null) {
                            wo = [];
                        }
                        /*
                        temp.requirements.forEach(function (item, index) {
                            wo.push(item.requirement_id)
                        })*/

                        this.guide.requirements_id = wo;


                    }
                }

                this.requirements_in_process = temp;

            }
            this.convertRequirementsIntoArray()
        },
        ChangeSelect() {
            this.setRequirement()
            this.convertRequirementsIntoArray()
        },
        haveObservation(file) {
            if (file === null) return false;
            if (file === undefined) return false;
            if (file.observations === undefined) return false;
            if (file.observations == null) return false;
            if (file.observations.length == null) return false;
            if (file.observations.length < 1) return false;

            return true
        },
        addRow() {
            this.$emit("addrow", this.guide);
            this.onClose()
        },
        onClose() {
            this.$emit("update:visible", false);
        },
        async onGetDataForNewFile() {
            this.loading = true;
            await this.$http
                .get(`${this.basePath}/create`)
                .then((response) => {
                    const data = response.data.data;
                    this.nextId = data.next_id;
                    this.currentYear = data.current_year;
                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
        },
        onGetFilenameFromPath(path) {
            if (path) {
                const parts = path.split("/");
                if (parts.length === 4) {
                    return parts[3];
                }
            }
            return "";
        },
        onInitializeForm() {
            this.guide = {
                id: null,
                guide: null,
                created_at: moment().format('YYYY-MM-DD HH:mm'),
                doc_office_id: null,
                date_take: null,
                date_end: null,
                documentary_guides_number_status_id: null,
                user_id: null,
                observation: null,
            };
        },
        onCreate() {
            this.loading = true;
            let newItem = true
            if (this.guides !== undefined) {
                if (this.guides.id !== undefined) {
                    newItem = false;
                }
            }
            this.tabActive = 'first'
            if (newItem === true) {
                this.title = "Crear tramite";
                this.onInitializeForm();
                // this.onGetDataForNewFile();
            } else {
                this.guide = this.guides
                this.title = "Editar tramite";
                /*
                this.filename = this.onGetFilenameFromPath(this.guide.attached_file);
                this.nextId = this.guide.id;
                this.number = this.guide.number;
                this.currentYear = this.guide.year;
                this.attachFile = null;
                this.ChangeSelect();
                */
            }
            this.loading = false;


        },

        clickAddStep() {
            if (this.guide.guides === undefined) this.guide.guides = [];
            if (this.guide.guides === null) this.guide.guides = [];
            if (this.guide.guides.length < 1) this.guide.guides = [];
            this.guide.guides.push({
                id: null,
                guide: null,
                created_at: moment().format('YYYY-MM-DD'),

                doc_office_id: null,
                date_take: null,
                date_end: null,
                documentary_guides_number_status_id: null,
                user_id: null,
                observation: null,


            });


        },
        handleRemove(file, fileList) {

            this.guide.file[this.index_file].filename = null
            this.guide.file[this.index_file].temp_path = null
            this.fileList = []
            this.index_file = null

        },
        onSuccess(response, file, fileList) {

            // console.log(response, file, fileList)
            this.fileList = fileList

            if (response.success) {

                this.index_file = response.data.index
                this.guide.file[this.index_file].filename = response.data.filename
                this.guide.file[this.index_file].temp_path = response.data.temp_path

            } else {
                this.cleanFileList()
                this.$message.error(response.message)
            }

            // console.log(this.guide.file)

        },
        cleanFileList() {
            this.fileList = []
        },
    },
};
</script>
