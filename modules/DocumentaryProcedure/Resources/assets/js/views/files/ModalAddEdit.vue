<template>
    <div>
        <el-dialog
            :title="title"
            :visible="visible"
            @close="onClose"
            @open="onCreate"
        >
            <form autocomplete="off" @submit.prevent="onSubmit">
                <div class="form-body">
                    <el-tabs v-model="tabActive">
                        <el-tab-pane class name="first">
                            <span slot="label">Datos del Expediente</span>

                            <div class="form-group row">
                                <!-- Código -->
                                <div class="col-6">
                                    <label>Código <span class="text-danger">*</span></label>
                                    <el-input v-model="nextId" readonly type="text"></el-input>
                                </div>
                                <!-- Tipo de documento  -->
                                <div
                                    :class="{ 'has-danger': errors.documentary_document_id }"
                                    class="form-group col-6"
                                >
                                    <label>Tipo de documento <span class="text-danger">*</span></label>
                                    <el-select
                                        v-model="form.documentary_document_id"
                                        @change="onGetDocumentNumber"
                                    >
                                        <el-option
                                            v-for="item in documentTypes"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.documentary_document_id"
                                        class="form-control-feedback"
                                        v-text="errors.documentary_document_id[0]"
                                    ></small>
                                </div>
                                <!-- Número de documento -->
                                <div
                                    :class="{ 'has-danger': errors.number }"
                                    class="form-group col-6"
                                >
                                    <label>Número de documento <span class="text-danger">*</span></label>
                                    <el-input v-model="form.number">
                                        <template slot="append">-{{ currentYear }}</template>
                                    </el-input>
                                    <small
                                        v-if="errors.number"
                                        class="form-control-feedback"
                                        v-text="errors.number[0]"
                                    ></small>
                                </div>
                                <!-- Folio -->
                                <div
                                    :class="{ 'has-danger': errors.number }"
                                    class="form-group col-6"
                                >
                                    <label>Folio <span class="text-danger">*</span></label>
                                    <el-input v-model="form.invoice"></el-input>
                                    <small
                                        v-if="errors.number"
                                        class="form-control-feedback"
                                        v-text="errors.number[0]"
                                    ></small>
                                </div>
                                <!-- Fecha de registro -->
                                <div
                                    :class="{ 'has-danger': errors.date_register }"
                                    class="form-group col-6"
                                >
                                    <label>Fecha de registro <span class="text-danger">*</span></label>
                                    <el-date-picker
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
                                    class="form-group col-6"
                                >
                                    <label>Hora de registro <span class="text-danger">*</span></label>
                                    <el-input v-model="form.time_register" placeholder="13:30:00">
                                    </el-input>
                                    <small
                                        v-if="errors.time_register"
                                        class="form-control-feedback"
                                        v-text="errors.time_register[0]"
                                    ></small>
                                </div>
                            </div>
                            <div :class="{ 'has-danger': errors.person_id }" class="form-group">
                                <label class="control-label font-weight-bold text-info">
                                    Remitente <span class="text-danger">*</span>
                                    <a href="#" @click.prevent="showDialogNewPerson = true"
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
                            <div :class="{ 'has-danger': errors.subject }" class="form-group">
                                <label>Asunto <span class="text-danger">*</span></label>
                                <el-input v-model="form.subject" type="text"></el-input>
                                <div v-if="errors.subject" class="invalid-feedback">
                                    {{ errors.subject[0] }}
                                </div>
                            </div>
                            <div :class="{ 'has-danger': errors.attachFile }" class="form-group">
                                <label>Archivo adjunto <span class="text-danger">*</span></label>
                                <el-button @click="onSearchFile">Buscar archivo</el-button>
                                <input
                                    ref="inputFile"
                                    class="hidden"
                                    type="file"
                                    @change="onSelectFile"
                                />
                                <span v-if="filename">{{ filename }}</span>
                                <div v-if="errors.attachFile" class="invalid-feedback">
                                    {{ errors.attachFile[0] }}
                                </div>
                            </div>


                            <div
                                :class="{ 'has-danger': errors.documentary_process_id }"
                                class="form-group"
                            >
                                <label>Proceso <span class="text-danger">*</span></label>
                                <el-select v-model="form.documentary_process_id">
                                    <el-option
                                        v-for="item in processes"
                                        :key="item.id"
                                        :label="item.name_price"
                                        :value="item.id"
                                    ></el-option>
                                </el-select>
                                <div v-if="errors.documentary_process_id" class="invalid-feedback">
                                    {{ errors.documentary_process_id[0] }}
                                </div>
                            </div>
                            <div
                                :class="{ 'has-danger': errors.documentary_process_id }"
                                class="form-group"
                            >
                                <label>Etapa <span class="text-danger">*</span></label>
                                <el-select v-model="form.documentary_office_id">
                                    <el-option
                                        v-for="item in offices"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"
                                    ></el-option>
                                </el-select>
                                <div v-if="errors.documentary_office_id" class="invalid-feedback">
                                    {{ errors.documentary_office_id[0] }}
                                </div>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane v-if="showArchives" class name="second">
                            <span slot="label">Archivos</span>
                            <table-archives
                                @updateFiles="updateFiles"
                            ></table-archives>
                        </el-tab-pane>

                        <el-tab-pane class name="thirdh">
                            <span slot="label">Datos Complementarios</span>


                            <vue-dropzone
                                id="dropzone"
                                ref="myVueDropzone"
                                :options="dropzoneOptions"

                                @vdropzone-upload-progress="uploadProgress"
                                @vdropzone-file-added="fileAdded"
                                @vdropzone-sending-multiple="sendingFiles"
                                @vdropzone-error="error"
                                @vdropzone-error-multiple="errormultiple"
                                @vdropzone-success-multiple="success"
                                @vdropzone-sending="sendingEvent"
                                @vdropzone-removed-file="getFileCount"
                                @vdropzone-file-added-manually="getFileCount">
                            </vue-dropzone>
                        </el-tab-pane>

                        <el-tab-pane class name="four" v-if="haveObservation(file)">
                            <span slot="label">Observaciones</span>

                            <table-observation></table-observation>

                        </el-tab-pane>
                    </el-tabs>
                    <div class="row text-center p-t-20">
                        <div class="col-6">
                            <el-button
                                :disabled="loading"
                                :loading="loading"
                                class="btn-block"
                                native-type="submit"
                                type="primary"
                            >Guardar
                            </el-button
                            >
                        </div>
                        <div class="col-6">
                            <el-button class="btn-block" @click="onClose">Cancelar</el-button>
                        </div>
                    </div>
                </div>
            </form>
        </el-dialog>
        <person-form
            :document_type_id="form.document_type_id"
            :external="true"
            :input_person="input_person"
            :showDialog.sync="showDialogNewPerson"
            type="customers"
        ></person-form>
    </div>
</template>

<script>
import moment from "moment";
import TableArchives from "./TableArchives";
import PersonForm from "../../../../../../../resources/js/views/tenant/persons/form.vue";
import OfficesRows from './Offices.vue';
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import {mapActions, mapState} from "vuex";
import TableObservation from "./TableObservation";

export default {
    components: {
        TableObservation,
        TableArchives,
        PersonForm,
        OfficesRows,
        vueDropzone: vue2Dropzone
    },
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
        /*
        file: {
            type: Object,
            required: false,
            default: {
                documentary_file_archives: [],
            },
        },
        */
    },
    data() {
        return {
            dropzoneOptions: {
                url: 'https://httpbin.org/post',
                // thumbnailWidth: 50,
                // thumbnailHeight: 50,
                //maxFilesize: 0.5,
                headers: {
                    "X-Requested-With": "X-Requested-With",
                    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content,
                },

                autoProcessQueue: false,

                // The way you want to receive the files in the server
                paramName: function (n) {
                    return "file[]";
                },
                dictDefaultMessage: "Arrastra y suelta los archivos aqui.",
                // includeStyling: false,
                // previewsContainer: false,
                parallelUploads: 10,
                maxFiles: 10,
                uploadMultiple: true,
            },
            tabActive: "first",
            tempAttachments: [],
            current_files: [],
            filesa: [],
            attachments: [],
            input_person: null,
            urlDropzone: null,
            form: {
                tempAttachments: [],
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
        };
    },
    created() {
        this.loadOffices()
        this.loadActions()
        this.loadCustomers()
        this.loadProcesses()
        this.loadFiles()
        this.loadDocumentTypes()
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
        ]),
        showArchives:function (){
            if(this.file === undefined) return false
            if(this.file === null) return false
            if(this.file.documentary_file_archives === undefined) return false
            if(this.file.documentary_file_archives === null) return false

            if( this.file.documentary_file_archives.length > 0 )
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
        haveObservation(file){
            if(file === null) return false;
            if(file === undefined) return false;
            if(file.observations === undefined) return false;
            if(file.observations == null) return false;
            if(file.observations.length == null) return false;
            if(file.observations.length < 1) return false;

            return true
        },
        updateFiles() {
            this.$emit("updateFiles");
        },
        fileAdded(file) {
            console.log("File Dropped => ", file);
            // Construct your file object to render in the UI
            this.filesa = [...this.filesa, file]
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
        // a middle layer function where you can change the XHR request properties
        sendingFiles(files, xhr, formData) {
            this.current_files = files;
        },
        // function where we get the upload progress
        uploadProgress(file, progress, bytesSent) {
            this.tempAttachments.map(attachment => {
                if (attachment.title === file.name) {
                    attachment.progress = `${Math.floor(progress)}`;
                }
            });
        },
        // called on successful upload of a file
        error(file, response) {
            if (response !== undefined && response.status !== undefined) {
                this.axiosError(response)
            }
            if (response !== undefined && response.message !== undefined) {
                this.errors = response.message
            }
            let dropzone = this.$refs.myVueDropzone.dropzone;
            file.previewTemplate.classList.toggle('dz-error');
            // file.previewTemplate.classList.toggle('dz-complete');

            if (file.status === 'error') file.status = 'added';
            // if(file.status === 'error') file.status = 'queued';
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
            // this.$refs.myVueDropzone.dropzone.enqueueFile(file)
        },
        // called on successful upload of a file
        success(file, response) {

            this.$emit("onUploadComplete", null);
            this.onClose();
            console.log("File uploaded successfully");
            console.log("Response is ->", response);
        },
        sendingEvent(file, xhr, formData) {
            formData.append("documentary_document_id", this.form.documentary_document_id);
            formData.append("documentary_process_id", this.form.documentary_process_id);
            formData.append("documentary_office_id", this.form.documentary_office_id);
            formData.append("number", this.form.number);
            formData.append("year", this.currentYear);
            formData.append("invoice", this.form.invoice);
            formData.append("date_register", this.form.date_register);
            formData.append("time_register", this.form.time_register);
            formData.append("person_id", this.form.person_id);
            if (this.form.sender) {
                formData.append("person", JSON.stringify(this.form.sender));
            }
            if (this.form.offices) {
                formData.append("offices", JSON.stringify(this.form.offices));
            }
            formData.append("subject", this.form.subject || "");
            formData.append("observation", this.form.observation || "");
            if (this.attachFile) {
                // formData.append("file", this.attachFile);
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

            data.append("documentary_office_id", this.form.documentary_office_id);
            data.append("documentary_document_id", this.form.documentary_document_id);
            data.append("documentary_process_id", this.form.documentary_process_id);
            data.append("number", this.form.number);
            data.append("year", this.currentYear);
            data.append("invoice", this.form.invoice);
            data.append("date_register", this.form.date_register);
            data.append("time_register", this.form.time_register);
            data.append("person_id", this.form.person_id);
            if (this.form.sender) {
                data.append("person", JSON.stringify(this.form.sender));
            }
            if (this.form.offices) {
                data.append("offices", JSON.stringify(this.form.offices));
            }
            data.append("subject", this.form.subject || "");
            data.append("observation", this.form.observation || "");
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
                .post(`${this.basePath}/${this.file.id}/update`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
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
                .post(`${this.basePath}/store`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
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
            const data = this.onGenerateData();
            let dropfile = this.$refs.myVueDropzone.dropzone.files.length;
            if (dropfile > 0) {

                if (this.file) {
                    this.$refs.myVueDropzone.dropzone.options.url = `${this.basePath}/${this.file.id}/update`
                } else {
                    this.$refs.myVueDropzone.dropzone.options.url = `${this.basePath}/store`
                }
                this.$refs.myVueDropzone.dropzone.processQueue()
            } else {
                if (this.file) {
                    this.onUpdate(data)
                } else {
                    this.onStore(data)
                }
            }

        },
        onClose() {
            this.tabActive = 'first';
            this.$refs.myVueDropzone.dropzone.removeAllFiles(true);
            this.$emit("update:visible", false);
        },
        async onGetDocumentNumber() {
            const params = {
                document_id: this.form.documentary_document_id,
            };
            this.loading = true;
            await this.$http
                .get(`${this.basePath}/document-number`, {params})
                .then((response) => {
                    const data = response.data.data;
                    this.form.number = data.number;
                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
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
            const date = moment();
            this.form = {
                date_register: date.format("YYYY-MM-DD"),
                time_register: date.format("H:mm:ss"),
                offices: []
            };
            this.filename = "";
            this.attachFile = null;
        },
        async onCreate() {
            if (this.file) {
                this.form = this.file;
                this.title = "Editar expediente";
                this.filename = this.onGetFilenameFromPath(this.form.attached_file);
                this.nextId = this.form.id;
                this.number = this.form.number;
                this.currentYear = this.form.year;
                this.attachFile = null;
            } else {
                this.title = "Crear expediente";
                this.onInitializeForm();
                await this.onGetDataForNewFile();
            }
            this.loading = true;
            await this.$http
                .get(`${this.basePath}/tables`)
                .then((response) => {
                    const data = response.data.data;
                    this.$store.commit('setCustomers', data.customers)
                    this.$store.commit('setDocumentTypes', data.document_types)
                    this.$store.commit('setActions', data.actions)
                    this.$store.commit('setProcesses', data.processes)
                    this.$store.commit('setOffices', data.offices)

                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
        },
    },
};
</script>
