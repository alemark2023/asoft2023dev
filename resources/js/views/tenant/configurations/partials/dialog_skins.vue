<template>
  <el-dialog title="Accesos Directos" :visible.sync="showDialog" @close="close" top="20vh" width="40%">
    <el-table :data="skins">
      <el-table-column prop="name" label="Nombre" width="150"></el-table-column>
      <el-table-column prop="filename" label="Archivo" width="150"></el-table-column>
    </el-table>
    <el-upload
      :headers="headers"
      :multiple="false"
      :on-remove="handleRemove"
      class="upload-demo"
      ref="upload"
      :action="`/configurations/visual/upload_skin`"
      :show-file-list="true"
      :on-success="onSuccess"
      :limit="1">
      <el-button slot="trigger" size="small" type="primary">Selecciona un archivo</el-button>
      <div slot="tip" class="el-upload__tip">Solo archivos css</div>
    </el-upload>
    <span slot="footer" class="dialog-footer">
      <el-button @click.prevent="close()">Cerrar</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import {mapActions, mapState} from "vuex";

  export default {
    props:['showDialog', 'skins'],
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
      return {
        form: {
          menu_a: null,
          menu_b: null,
          menu_c: null,
          menu_d: null,
        },
        formLabelWidth: '120px',
        modules: [],
        headers: headers_token,
        index_file: null,
        records: [],
      };
    },
    created() {
      this.$store.commit('setConfiguration', this.configuration);
      this.loadConfiguration();
      this.getRecords();
      this.initForm();
    },
    methods: {
      ...mapActions([
          'loadConfiguration',
      ]),
      getRecords() {
        this.$http.get(`/configurations/visual/get_menu`) .then(response => {
          if (response.data !== ''){
            this.modules = response.data.modules;
          }
        });
      },
      initForm() {
        this.form = {
          menu_a: this.config.top_menu_a_id,
          menu_b: this.config.top_menu_b_id,
          menu_c: this.config.top_menu_c_id,
          menu_d: this.config.top_menu_d_id
        }
      },
      close() {
          this.$emit('update:showDialog', false)
      },
      handleRemove(file, fileList) {
        this.records[this.index_file].filename = null
        this.records[this.index_file].temp_path = null
        this.fileList = []
        this.index_file = null
      },
      onSuccess(response, file, fileList) {
        this.fileList = fileList
        if (response.success) {
          // this.index_file = response.data.index
          this.$message.success(response.message)
          location.reload();
        } else {
          this.cleanFileList()
          this.$message.error(response.message)
          console.log(response.message);
        }
      },
      cleanFileList(){
        this.fileList = []
      },
    }
  };
</script>

<style>
.v-modal {
  z-index: 1 !important;
}
</style>