<template>

    <div class="card mb-0">
        <div class="card-header bg-info">

            <h3 class="my-0">
                {{ title }}
            </h3>
        </div>
        <div class="card-body">
            <!-- <el-dialog
                :title="title"
                :visible="visible"
                @close="onClose"
                @open="onCreate"
            >
            -->
            <div class="form-body row">


                <!-- cliente -->
                <div :class="{ 'has-danger': errors.person_id }"
                     class="form-group col-sm-12 col-md-6 col-lg-4 ">

                    <label class="control-label font-weight-bold text-info">
                        Cliente <span class="text-danger">*</span>
                        <a href="#"
                           @click.prevent="showDialogNewPerson = true"
                        >[+ Nuevo]</a
                        >
                    </label>
                    <el-select
                        v-model="form.person_id"
                        :loading="loading"
                        :remote-method="searchRemoteCustomers"
                        class="border-left rounded-left border-info"
                        filterable
                        placeholder="Escriba el nombre o número de documento del cliente"
                        popper-class="el-select-customers"
                        remote
                        @change="changeCustomer"
                    >
                        <el-option
                            v-for="option in customers"
                            :key="option.id"
                            :label="option.description"
                            :value="option.id"
                        ></el-option>
                    </el-select>
                    <small
                        v-if="errors.person_id"
                        class="form-control-feedback"
                        v-text="errors.person_id[0]"
                    ></small>
                </div>

                <!-- Tipo de tramite -->
                <div
                    :class="{ 'has-danger': errors.documentary_process_id }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>Tipo de tramite <span class="text-danger">*</span></label>
                    <el-select
                        :loading="loading"
                        v-model="form.documentary_process_id"
                        @change="ChangeSelect"
                    >
                        <el-option
                            v-for="item in processes"
                            :key="item.id"
                            :label="item.name_price"
                            :value="item.id"
                            @change="ChangeSelect"
                        ></el-option>
                    </el-select>
                    <div v-if="errors.documentary_process_id"
                         class="invalid-feedback">
                        {{ errors.documentary_process_id[0] }}
                    </div>
                </div>

                <!-- Folio -->
                <div
                    :class="{ 'has-danger': errors.number }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">

                <label>Código de expediente (interno) </label>
                    <el-input                         :loading="loading"
                                                      v-model="form.invoice"></el-input>
                    <small
                        v-if="errors.invoice"
                        class="form-control-feedback"
                        v-text="errors.invoice[0]"
                    ></small>
                </div>

                <!-- Fecha de registro -->
                <div
                    :class="{ 'has-danger': errors.date_register }"
                    class="form-group col-sm-12 col-md-3 col-lg-3 ">
                    <label>Fecha de registro <span class="text-danger">*</span></label>
                    <el-date-picker
                        :loading="loading"
                        v-model="form.date_register"
                        placeholder="Selecciona una fecha"
                        type="date"
                    >
                    </el-date-picker>
                    <small
                        v-if="errors.date_register"
                        class="form-control-feedback"
                        v-text="errors.date_register[0]"
                    ></small>
                </div>

                <!-- >Hora de registro -->
                <div
                    :class="{ 'has-danger': errors.time_register }"
                    class="form-group col-sm-12 col-md-3 col-lg-3 ">

                    <label>Hora de registro <span class="text-danger">*</span></label>
                    <el-input v-model="form.time_register"
                              :loading="loading"
                              placeholder="13:30:00">
                    </el-input>
                    <small
                        v-if="errors.time_register"
                        class="form-control-feedback"
                        v-text="errors.time_register[0]"
                    ></small>
                </div>


                <div class="row">
                    <div class="col-12">
                        <label>
                            Procesos
                        </label>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Num. De Seguimiento</th>
                                    <th>Fecha/Hora De Registro</th>
                                    <th>Etapa</th>
                                    <th>Tiempo Que Toma El Tramite</th>
                                    <th>Fecha Concluida</th>
                                    <th>Estado</th>
                                    <th>Responsable</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                    <!--                                        <th>herramientas /opciones</th>-->
                                </tr>
                                </thead>
                                <tbody v-if="form.guides.length > 0">
                                <tr v-for="(row, index) in form.guides">

                                    <template v-if="row.visible !== false">
                                    <td> {{ index + 1 }}</td>
                                    <td>
                                        {{ row.guide }}

                                    </td>
                                    <td>
                                        {{ row.created_at }}
                                    </td>
                                    <td>
                                        {{ getStage(row.doc_office_id) }}
                                    </td>
                                    <td>
                                        {{ row.date_take }}
                                    </td>
                                    <td>
                                        {{ row.date_end }}
                                    </td>
                                    <td>
                                        {{ getStatus(row.documentary_guides_number_status_id) }}
                                    </td>
                                    <td>
                                        {{ getUser(row.user_id) }}
                                    </td>
                                    <td>
                                        {{ row.observation }}
                                    </td>
                                    <td>

                                        <button class="btn waves-effect waves-light btn-xs btn-primary m-1__2"

                                                type="button"
                                                @click.prevent="clickEditStep(row)">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <br>


                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="row=clickRemoveItem(index, row)">x
                                        </button>


                                        <!--                                            ADJUNTAR ARCHIVO<br>-->

<!--                                    <br>    CANCELAR Y ARCHIVAR<br>-->
                                    </td>
                                    </template>
                                </tr>
                                </tbody>

                            </table>

                        </div>
                        <div v-show="data_load"
                             class="col-12 text-center">
                            <label class="control-label">
                                <a class="btn"
                                   href="#"
                                   @click.prevent="clickAddStep">
                                    <i class="fa fa-plus font-weight-bold text-info"></i>
                                    <span style="color: #777777">Agregar Etapa</span></a>

                            </label>
                        </div>
                    </div>
                </div>
                <!--
                <div v-if="haveObservation(file)">
                    <table-observation></table-observation>
                </div>
                -->


                <div class="col-6">
                    &nbsp;
                </div>
                <div class="row text-center col-6 p-t-20">

                    <div class="col-6">
                        <el-button class="btn-block"
                                   @click="onClose">Cancelar
                        </el-button>
                    </div>
                    <div v-if="form.guides.length > 0"
                         class="col-6">
                        <el-button
                            :disabled="canSubmit"
                            :loading="loading"
                            class="btn-block"
                            native-type="submit"
                            type="primary"
                            @click.prevent="onSubmit"
                        >Guardar
                        </el-button
                        >
                    </div>
                </div>


            </div>
            <!-- </el-dialog> -->

            <add-process-modal
                :guides="editGuide"
                :visible.sync="showDialogNewProcess"
                @addrow="addStep"
                @closeElement="clearStep"
            >
            </add-process-modal>

            <person-form
                :document_type_id="form.document_type_id"
                :external="true"
                :input_person="input_person"
                :showDialog.sync="showDialogNewPerson"
                type="customers"
            ></person-form>



        </div>
    </div>
</template>

<script>
import moment from "moment";
// import TableArchives from "./TableArchives";
import PersonForm from "../../../../../../../resources/js/views/tenant/persons/form.vue";
// import OfficesRows from './Offices.vue';
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import {mapActions, mapState} from "vuex";
// import TableObservation from "./TableObservation";
import AddProcessModal from './ModalAddProcess.vue';

export default {
    components: {
        // TableObservation,
        AddProcessModal,
        // TableArchives,
        PersonForm,
        // OfficesRows,
        vueDropzone: vue2Dropzone
    },
    props: {
        /*
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
        */
        local_files: {
            type: Array,
            required: true,
        },
        local_offices: {
            type: Array,
            required: true,
        },
        local_processes: {
            type: Array,
            required: false,
        },
        local_actions: {
            type: Array,
            required: false,
        },
        local_customers: {
            type: Array,
            required: false,
        },
        local_documentTypes: {
            type: Array,
            required: false,
        },
        local_statusDocumentary: {
            type: Array,
            required: false,
        },
        recordid: {
            type: Number,
            required: false,
        },
    },
    data() {
        return {
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
            current_files: [],

            editGuide: {},
            attachments: [],
            data_load: false,
            input_person: null,
            urlDropzone: null,
            form: {
                date_register: moment().format("YYYY-MM-DD"),
                time_register: moment().format("H:mm:ss"),
                offices: [],
                guides: [],
                documentary_process_id: null,
                tempAttachments: [],
                requirements_id: [],
                full_stages: [],
                disable: false,
            },
            disable: false,
            title: "",
            loading: false,
            basePath: "/documentary-procedure/files_simplify",
            showDialogNewPerson: false,
            showDialogNewProcess: false,
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
        this.$store.commit('setOffices', this.local_offices)
        this.$store.commit('setFiles', this.local_files)
        this.$store.commit('setProcesses', this.local_processes)
        this.$store.commit('setActions', this.local_actions)
        this.$store.commit('setCustomers', this.local_customers)
        this.$store.commit('setDocumentTypes', this.local_documentTypes)
        this.$store.commit('setStatusDocumentary', this.local_statusDocumentary)
        this.$store.commit('setFile', null)

        this.onCreate();
    },
    mounted() {

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
            'sellers',
            'statusDocumentary',

        ]),
        onlyShow() {
            if (this.form !== undefined && this.form.disable !== undefined) return this.form.disable
            return false;
        },
        canSubmit() {
            // if(this.loading)
            if (
                this.form.documentary_process_id !== null &&
                this.form.person_id !== null &&
                this.loading === false
            ) {
                return false
            }
            return true;
        },
        hasRequiements: function () {

            if (
                this.requirements_in_process !== undefined &&
                this.requirements_in_process !== null &&
                this.requirements_in_process.requirements !== undefined &&
                this.requirements_in_process.requirements !== null &&
                this.requirements_in_process.requirements.length > 0
            ) {
                return true;
            }
            return false;
        },
        showArchives: function () {
            if (this.form === undefined) return false
            if (this.form === null) return false
            if (this.form.documentary_file_archives === undefined) return false
            if (this.form.documentary_file_archives === null) return false

            if (this.form.documentary_file_archives.length > 0)
                return true;
            return false;
        }

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
            return this.form.requirements_id;

        },
        setRequirement() {
            this.requirements_in_process = [];
            if (
                (this.form !== undefined || this.form !== null) &&
                (this.form.documentary_process_id !== undefined) &&
                (this.form.documentary_process_id !== null)
            ) {

                let temp = this.processes.find((it) => {
                    return it.id === this.form.documentary_process_id
                });
                if (temp.requirements !== undefined) {
                    if (temp.requirements !== null) {
                        let wo = this.form.requirements_id;

                        if (wo === undefined || wo == null) {
                            wo = [];
                        }
                        /*
                        temp.requirements.forEach(function (item, index) {
                            wo.push(item.requirement_id)
                        })*/

                        this.form.requirements_id = wo;


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
        updateFiles() {
            this.$emit("updateFiles");
        },
        fileAdded(file) {
            console.log("File Dropped => ", file);
            let attachment = {};
            attachment._id = file.upload.uuid;
            attachment.title = file.name;
            attachment.type = "file";
            attachment.extension = "." + file.type.split("/")[1];
            attachment.user = JSON.parse(localStorage.getItem("user"));
            attachment.content = "File Upload by Select or Drop";
            attachment.thumb = file.dataURL;
            attachment.thumb_list = file.dataURL;
            attachment.isLoading = true;
            attachment.progress = null;
            attachment.size = file.size;
            this.tempAttachments = [...this.tempAttachments, attachment];
        },
        sendingFiles(files, xhr, formData) {
            this.current_files = files;
        },
        uploadProgress(file, progress, bytesSent) {
            this.tempAttachments.map(attachment => {
                if (attachment.title === file.name) {
                    attachment.progress = `${Math.floor(progress)}`;
                }
            });
        },
        error(file, response) {
            if (response !== undefined && response.status !== undefined) {
                this.axiosError(response)
            }
            if (response !== undefined && response.message !== undefined) {
                this.errors = response.message
            }
            let dropzone = this.$refs.myVueDropzone.dropzone;
            file.previewTemplate.classList.toggle('dz-error');
            if (file.status === 'error') file.status = 'added';
            this.addOnError(dropzone, file)

        },
        errormultiple(file, response, other) {
            console.log('errormultiple')
            if (response.message !== undefined) {
                response.status = 422
                if (response.data === undefined) {
                    response.data = {
                        message: response.message,
                    }


                }
            }
            if (response !== undefined && response.status !== undefined) {
                this.axiosError(response)
            }
        },
        addOnError(dropzone, file) {
            dropzone.enqueueFile(file)
        },
        // called on successful upload of a file
        success(file, response) {

            this.$emit("onUploadComplete", null);
            this.onClose();
        },
        sendingEvent(file, xhr, formData) {
            formData.append("date_register", this.form.date_register);
            formData.append("time_register", this.form.time_register);
            formData.append("offices", this.form.offices);
            formData.append("documentary_process_id", this.form.documentary_process_id);
            formData.append("requirements_id", JSON.stringify(this.form.requirements_id));
            formData.append("person_id", this.form.person_id);
            formData.append("invoice", this.form.invoice);

            formData.append("year", this.currentYear);

            if (this.form.sender) {
                formData.append("person", JSON.stringify(this.form.sender));
            }
            if (this.form.offices) {
                formData.append("offices", JSON.stringify(this.form.offices));
            }
            formData.append("invoice", this.form.invoice || "");
            formData.append("observation", this.form.observation || "");
            if (this.attachFile) {
                formData.append("attachFile", this.attachFile);
            }
            for (let files in this.$refs.myVueDropzone.dropzone.files) {
                formData.append("attachments[]", this.$refs.myVueDropzone.dropzone.files[files]);
            }
        },
        getFileCount() {
            if ('undefined' !== typeof this.$refs.myVueDropzone.dropzone) {
                this.fileCount = this.$refs.myVueDropzone.dropzone.files.length
            } else {
                this.fileCount = 0;
            }
        },
        addFile() {
            var file = {size: 123, name: "XYZ.pdf"};
            var url = "xyz.pdf";
            this.$refs.myVueDropzone.manuallyAddFile(file, url);
        },
        onSearchFile() {
            this.$refs.inputFile.click();
        },
        onSelectFile(event) {
            const files = event.target.files;
            if (files.length > 0) {
                this.attachFile = files[0];
                this.filename = this.attachFile.name;
            } else {
                this.attachFile = null;
                this.filename = "";
            }
        },
        onGenerateData() {
            const data = new FormData();

            data.append("date_register", this.form.date_register);
            data.append("time_register", this.form.time_register);
            data.append("offices", this.form.offices);
            data.append("documentary_process_id", this.form.documentary_process_id);
            data.append("requirements_id", JSON.stringify(this.form.requirements_id));
            data.append("person_id", this.form.person_id);
            data.append("invoice", this.form.invoice);

            data.append("year", this.currentYear);

            if (this.form.sender) {
                data.append("person", JSON.stringify(this.form.sender));
            }
            if (this.form.offices) {
                data.append("offices", JSON.stringify(this.form.offices));
            }
            if (this.attachFile) {
                data.append("attachFile", this.attachFile);
            }
            for (let files in this.$refs.myVueDropzone.dropzone.files) {
                let cor = this.$refs.myVueDropzone.dropzone.files[files]
                data.append("attachments[]", cor);
            }
            return data;
        },
        changeCustomer() {
            const customer = this.customers
                .filter((c) => this.form.person_id == c.id)
                .reduce((c) => c);
            this.form.sender = {
                name: customer.name,
                address: customer.address,
                number: customer.number,
                identity_document_type_id: customer.identity_document_type_id,
            };
        },
        searchRemoteCustomers(input) {
            if (input.length > 0) {
                this.loading = true;
                let parameters = `input=${input}&document_type_id=&operation_type_id=`;

                this.$http
                    .get(`/documents/search/customers?${parameters}`)
                    .then((response) => {
                        this.$store.commit('setCustomers', response.data.customers)
                    })
                    .catch((error) => this.axiosError(error))
                    .finally(() => (this.loading = false));
            }
        },
        onUpdate(data) {
            this.loading = true;
            this.$http
                .post(`${this.basePath}/${this.form.id}/update`, this.form)
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$emit("onUpdateItem", response.data.data);
                    this.onClose();
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    this.axiosError(error);
                });
        },
        onStore(data) {
            this.loading = true;
            this.$http
                .post(`${this.basePath}/store`, this.form)
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$emit("onAddItem", response.data.data);
                    this.onClose();
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    this.axiosError(error);
                });
        },
        onSubmit() {
            // const data = this.onGenerateData();
            let data = '';
            console.log('e1')
                if (this.form && this.form.id) {
                    console.log('e2')
                    this.onUpdate(data)
                } else {
                    console.log('e3')
                    this.onStore(data)
                }
            console.log('e4')
        },
        onClose() {
            window.location = '/documentary-procedure/files_simplify'
        },
        onGetDocumentNumber() {
            const params = {
                document_id: this.form.documentary_document_id,
            };
            this.loading = true;
            this.$http
                .get(`${this.basePath}/document-number`, {params})
                .then((response) => {
                    const data = response.data.data;
                    this.form.number = data.number;
                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
        },
        onGetDataForNewFile() {
            this.loading = true;
            this.$http
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
            const date = moment();
            this.form = {
                date_register: date.format("YYYY-MM-DD"),
                time_register: date.format("H:mm:ss"),
                offices: [],
                guides: [],
                documentary_process_id: null,
                tempAttachments: [],
                requirements_id: [],
                full_stages: [],
                disable: false
            };
            this.filename = "";
            this.attachFile = null;
            this.setRequirement()
        },
        onCreate() {
            this.loading = true;
            this.tabActive = 'first'
            this.$http
                .get(`${this.basePath}/tables`)
                .then((response) => {
                    const data = response.data.data;
                    console.error(data)
                    this.$store.commit('setCustomers', data.customers)
                    this.$store.commit('setDocumentTypes', data.document_types)
                    this.$store.commit('setActions', data.actions)
                    this.$store.commit('setSellers', data.sellers)
                    // this.$store.commit('setProcesses', data.processes)
                    // this.$store.commit('setOffices', data.offices)
                    this.data_load = true;

                })
                .catch((error) => this.axiosError(error))
                .finally(() => {
                    if (this.file !== null) {

                        let f = this.file;
                        if (f.offices == null) f.offices = [];
                        if (f.documentary_process_id == null) f.documentary_process_id = null;
                        if (f.tempAttachments == null) f.tempAttachments = [];
                        if (f.requirements_id == null) f.requirements_id = [];
                        if (f.full_stages == null) f.full_stages = [];
                        if (f.disable == null) f.disable = this.disable;
                        if (f.guides === undefined) f.guides = [];
                        if (f.guides === null) f.guides = [];
                        this.form = f


                        this.title = "Editar tramite";
                        this.filename = this.onGetFilenameFromPath(this.form.attached_file);
                        this.nextId = this.form.id;
                        this.number = this.form.number;
                        this.currentYear = this.form.year;
                        this.attachFile = null;
                        this.ChangeSelect();
                    }
                    else if (this.recordid !== undefined){
                        this.$http
                            .post(`${this.basePath}/ask/${this.recordid}`)
                            .then((response) => {
                                this.form = response.data;
                            })

                    }
                    else if (this.file == null) {
                        this.title = "Crear tramite";
                        this.onInitializeForm();
                        // this.onGetDataForNewFile();
                    }


                    this.loading = false;
                });






        },

        clickAddStep() {
            this.clearStep()
            if (this.data_load == true) {
                this.showDialogNewProcess = true
            }
            /*
            let newGuide = {
                id: null,
                guide: '',
                created_at: moment().format('YYYY-MM-DD HH:mm'),
                doc_office_id: '',
                date_take: '',
                date_end: '',
                documentary_guides_number_status_id: '',
                user_id: '',
                observation: '',

            };
            this.form.guides.push(newGuide);
            */

        },
        addStep(row) {
            this.form.guides.push(row);
            this.editGuide = {};

        },
        clearStep() {
            this.editGuide = {};

        },
        getStage(doc_office_id) {
            if (this.offices === undefined) return '';
            if (this.offices.length < 1) return '';
            let stageT = this.offices.find((it) => {
                return it.id === doc_office_id
            });
            let retu = '';
            if (stageT !== undefined) {
                retu = stageT.name
            }
            return retu
        },
        getUser(user_id) {

            if (this.sellers === undefined) return '';
            if (this.sellers.length < 1) return '';

            let stageT = this.sellers.find((it) => {
                return it.id === user_id
            });
            let retu = '';
            if (stageT !== undefined) {
                retu = stageT.name
            }
            return retu
        },
        getStatus(documentary_guides_number_status_id) {
            if (this.statusDocumentary === undefined) return '';

            if (this.statusDocumentary.length < 1) return '';


            let stageT = this.statusDocumentary.find((it) => {
                return it.id === documentary_guides_number_status_id
            });
            let retu = '';
            if (stageT !== undefined) {
                retu = stageT.name
            }
            return retu
        },
        clickEditStep(row) {
            if (this.data_load == true) {
                this.showDialogNewProcess = true
                this.editGuide = row;
            }
        },

        clickRemoveItem(index, row) {
            row.visible = false
            // this.form.guides.splice(index, 1)
            return row;
        },

    },
};
</script>
