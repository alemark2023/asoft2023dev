<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Plantilla PDF</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button class="btn btn-custom btn-sm  mt-2 mr-2"
                        type="button"
                        @click="addSeeder"><i class="el-icon-refresh"></i>
                    Actualizar listado
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-body pb-5">
                <div class="row">
                    <div class="col-3">
                        <label class="control-label">Establecimiento</label>
                        <el-select v-model="form.establishment_id"
                                   @change="changeEstablishment()">
                            <el-option v-for="option in establishments"
                                       :key="option.id"
                                       :label="option.description"
                                       :value="option.id"></el-option>
                        </el-select>
                    </div>
                    <div class="col-3">
                        <label class="control-label">Plantilla actual</label>
                        <el-input v-model="form.current_format"
                                  readonly></el-input>
                        <small v-if="form.current_format"
                               style="cursor:pointer">
                            <a @click="viewImage(form.current_format)">
                                Ver plantilla
                            </a>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div v-for="(o, index) in formatos"
                         class="my-2 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                        <el-card :id="o.formats"
                                 :body-style="{ padding: '0px' }">
                            <a @click="viewImage(o.formats)">
                                <img :src="path.origin+'/templates/pdf/'+o.formats+'/image.png'"
                                     class="image"
                                     style="width: 100%"></a>
                            <div style="padding: 14px;">
                                <span class="text-center">{{ o.formats }}</span>
                                <div v-if="form.establishment_id"
                                     class="bottom clearfix text-right">
                                    <!-- <el-button type="submit" class="button" @change="changeFormat(o.formats)">Activo</el-button> -->
                                    <el-radio v-model="form.current_format"
                                              :label="o.formats"
                                              @change="changeFormat(o.formats)">
                                        <span v-if="form.current_format == o.formats">Activo</span>
                                        <span v-else>Activar</span>
                                    </el-radio>
                                </div>
                            </div>
                        </el-card>
                    </div>
                </div>
            </div>
        </div>
        <el-dialog
            :visible.sync="modalImage"
            width="60">
            <span>
                <img :src="path.origin+'/templates/pdf/'+template+'/image.png'"
                     class="image"
                     style="width: 100%">
            </span>
            <span slot="footer"
                  class="dialog-footer">
                <el-button @click="modalImage = false">Cerrar</el-button>
                <el-button v-if="form.establishment_id"
                           type="primary"
                           @click="changeFormat(template)">Activar</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>

export default {
    props: [
        'path_image',
        'typeUser',
        'establishments'
    ],

    data() {
        return {
            loading_submit: false,
            resource: 'configurations',
            errors: {},
            form: {},
            formatos: [],
            path: location,
            modalImage: false,
            template: ''
        }
    },
    async created() {

        // await this.$http.get(`/${this.resource}/record`) .then(response => {
        //     if (response.data !== ''){
        //         // console.log(response.data);
        //         this.form = response.data.data;
        //     }
        //     // console.log(this.placeholder)
        // });

        await this.$http.get(`/${this.resource}/getFormats`).then(response => {
            if (response.data !== '') this.formatos = response.data.filter(r => this.imageGuide(r.formats))
            // console.log(this.formatos)
        });

    },
    methods: {
        changeFormat(value) {
            this.modalImage = false
            this.form = {
                formats: value,
                establishment: this.form.establishment_id,
            }

            this.$http.post(`/${this.resource}/changeFormat`, this.form).then(response => {
                this.$message.success(response.data.message);
                location.reload()
            })

        },
        changeEstablishment() {
            var establishment = this.form.establishment_id;
            var selected = _.filter(this.establishments, {'id': establishment})[0];
            // console.log(selected.template_pdf);
            this.form.current_format = selected.template_pdf;
        },
        addSeeder() {
            var ruta = location.host
            this.$http.get(`/${this.resource}/addSeeder`).then(response => {
                this.$message.success(response.data.message);
                location.reload()
            })
        },
        viewImage($value) {
            this.template = $value

            this.modalImage = true
        },
        imageGuide(folder) {
            let url = this.path.origin + '/templates/pdf/' + folder + '/image.png'
            // console.log(url)
            var http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            // console.log(http.status)
            return http.status != 404;
        },
    }
}
</script>
