<template>
  <div>
    <div class="d-flex justify-content-between align-items-center">
      <strong>Oficina de destino</strong>
      <el-button @click="onAddOffice">+ Agregar oficina</el-button>
    </div>
    <div class="row position-relative" v-for="(o, i) in form.offices" :key="i">
      <el-button type="danger" class="btn-delete-office">
        <i class="fa fa-trash"></i>
        <span class="ml-2">Eliminar</span>
      </el-button>
      <div class="col-12">
        <hr />
      </div>
      <div class="form-group col-6">
        <label>Oficina</label>
        <el-select v-model="o.documentary_office_id">
          <el-option
            v-for="item in offices"
            :key="item.id"
            :value="item.id"
            :label="item.name"
          ></el-option>
        </el-select>
      </div>
      <div class="form-group col-6">
        <label>Acción</label>
        <el-select v-model="o.documentary_action_id">
          <el-option
            v-for="item in actions"
            :key="item.id"
            :value="item.id"
            :label="item.name"
          ></el-option>
        </el-select>
      </div>
      <div class="form-group col-12">
        <label>Observación</label>
        <el-input type="textarea" v-model="o.observation"></el-input>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    offices: {
      type: Array,
      required: true,
    },
    actions: {
      type: Array,
      required: true,
    },
    form: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      officesSelected: [],
    };
  },
  beforeMount() {
    this.onAddOffice();
  },
  methods: {
    onAddOffice() {
      this.form.offices.push({
        documentary_action_id: null,
        documentary_office_id: null,
        observation: null,
      });
    },
  },
};
</script>
<style land="scss">
.btn-delete-office {
  position: absolute;
  right: 16px;
  top: 44px;
  padding: 4px 4px;
  z-index: 2;
}
</style>
