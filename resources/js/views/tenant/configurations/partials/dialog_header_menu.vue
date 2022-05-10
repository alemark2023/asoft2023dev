<template>
  <div class="d-md-none ml-1 d-lg-block" style="height: inherit;">

    <a v-if="menu.menu_a != ''" class="topbar-links" :href="menu.menu_a.route_path" :title="menu.menu_a.description" data-toggle="tooltip" data-placement="bottom">
        <i class="fas fa-fw fa-plus" aria-hidden="true"></i>
        <span>{{menu.menu_a.label_menu}}</span>
    </a>
    <a v-if="menu.menu_b != ''" class="topbar-links" :href="menu.menu_b.route_path" :title="menu.menu_b.description" data-toggle="tooltip" data-placement="bottom">
        <i class="fas fa-fw fa-plus" aria-hidden="true"></i>
        <span>{{menu.menu_b.label_menu}}</span>
    </a>
    <a v-if="menu.menu_c != ''" class="topbar-links" :href="menu.menu_c.route_path" :title="menu.menu_c.description" data-toggle="tooltip" data-placement="bottom">
        <i class="fas fa-fw fa-plus" aria-hidden="true"></i>
        <span>{{menu.menu_c.label_menu}}</span>
    </a>
    <a v-if="menu.menu_d != ''" class="topbar-links" :href="menu.menu_d.route_path" :title="menu.menu_d.description" data-toggle="tooltip" data-placement="bottom">
        <i class="fas fa-fw fa-plus" aria-hidden="true"></i>
        <span>{{menu.menu_d.label_menu}}</span>
    </a>
    <a class="topbar-links" href="#" title="editar accesos directos" data-toggle="tooltip" data-placement="bottom" @click="showDialog = true">
      <i class="fas fa-fw fa-pen" aria-hidden="true"></i>
      <span><i class="fas fa-ellipsis-h"></i></span>
    </a>
    <el-dialog title="Accesos Directos" :visible="showDialog" @close="close" top="20vh" width="40%">
      <el-form :model="form">
        <el-form-item label="Menu 1" :label-width="formLabelWidth">
          <el-select v-model="form.menu_a" placeholder="Menu 1">
            <el-option v-for="option in modules"
                      :key="'a'+option.id"
                      :label="option.route_path"
                      :value="option.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Menu 2" :label-width="formLabelWidth">
          <el-select v-model="form.menu_b" placeholder="Menu 2" required>
            <el-option v-for="option in modules"
                      :key="'b'+option.id"
                      :label="option.route_path"
                      :value="option.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Menu 3" :label-width="formLabelWidth">
          <el-select v-model="form.menu_c" placeholder="Menu 3" required>
            <el-option v-for="option in modules"
                      :key="'c'+option.id"
                      :label="option.route_path"
                      :value="option.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Menu 4" :label-width="formLabelWidth">
          <el-select v-model="form.menu_d" placeholder="Menu 4" required>
            <el-option v-for="option in modules"
                      :key="'one'+option.id"
                      :label="option.route_path"
                      :value="option.id"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click.prevent="close()">Cancel</el-button>
        <el-button type="primary" @click.prevent="clickSubmit">Guardar</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  export default {
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
        showDialog: false,
        menu: {}
      };
    },
    created() {
      this.getRecords();
    },
    methods: {
      getRecords() {
        this.$http.get(`/configurations/visual/get_menu`) .then(response => {
          if (response.data !== ''){
            this.modules = response.data.modules;
            this.menu = {
              menu_a: response.data.menu.top_menu_a,
              menu_b: response.data.menu.top_menu_b,
              menu_c: response.data.menu.top_menu_c,
              menu_d: response.data.menu.top_menu_d
            }
            this.form = {
              menu_a: response.data.menu.top_menu_a.id,
              menu_b: response.data.menu.top_menu_b.id,
              menu_c: response.data.menu.top_menu_c.id,
              menu_d: response.data.menu.top_menu_d.id
            }
          }
        });
      },
      clickSubmit(){
        this.$http.post(`/configurations/visual/set_menu`, this.form).then(response => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.menu = {
              menu_a: response.data.menu.top_menu_a,
              menu_b: response.data.menu.top_menu_b,
              menu_c: response.data.menu.top_menu_c,
              menu_d: response.data.menu.top_menu_d
            }
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
          this.showDialog = false;
      },
    }
  };
</script>

<style>
.v-modal {
  z-index: 1 !important;
}
</style>