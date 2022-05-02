<template>
  <el-dialog title="Accesos Directos" :visible.sync="showDialog" @close="close" top="20vh" width="40%">
    <el-form :model="form">
      <el-form-item label="Menu 1" :label-width="formLabelWidth">
        <el-select v-model="form.menu_a" placeholder="Menu 1">
          <el-option v-for="option in modules"
                    :key="'a'+option.id"
                    :label="option.description"
                    :value="option.id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Menu 2" :label-width="formLabelWidth">
        <el-select v-model="form.menu_b" placeholder="Menu 2" required>
          <el-option v-for="option in modules"
                    :key="'b'+option.id"
                    :label="option.description"
                    :value="option.id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Menu 3" :label-width="formLabelWidth">
        <el-select v-model="form.menu_c" placeholder="Menu 3" required>
          <el-option v-for="option in modules"
                    :key="'c'+option.id"
                    :label="option.description"
                    :value="option.id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Menu 4" :label-width="formLabelWidth">
        <el-select v-model="form.menu_d" placeholder="Menu 4" required>
          <el-option v-for="option in modules"
                    :key="'one'+option.id"
                    :label="option.description"
                    :value="option.id"></el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click.prevent="close()">Cancel</el-button>
      <el-button type="primary" @click.prevent="clickSubmit">Guardar</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import {mapActions, mapState} from "vuex";

  export default {
    props:['showDialog'],
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
        modules: []
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
      clickSubmit(){
        this.$http.post(`/configurations/visual/set_menu`, this.form).then(response => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.$store.commit('setConfiguration', response.data.configuration);
          }
          else {
            this.$message.error(response.data.message);
          }
        }).catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
          }
          else {
            console.log(error);
          }
        }).then(() => {
          this.loading_submit = false;
        });
        this.close()
      },
      close() {
          this.$emit('update:showDialog', false)
      },
    }
  };
</script>

<style>
.v-modal {
  z-index: 1 !important;
}
</style>