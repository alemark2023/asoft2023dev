<template>
  <div>
    <el-select v-model="selected_id" placeholder="Select">
      <el-option v-for="item in customers" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </el-select>
    <el-badge :value="12" class="item">
      <el-button size="small">Total facturas</el-button>
    </el-badge>
    <el-table :data="tableData3" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="props">
          <p>Estado: {{ props.row.state }}</p>
          <p>Ciudad: {{ props.row.city }}</p>
          <p>Dirección: {{ props.row.address }}</p>
          <p>Código postal: {{ props.row.zip }}</p>
        </template>
      </el-table-column>
      <el-table-column label="Fecha" prop="date"></el-table-column>
      <el-table-column label="Nombre" prop="name"></el-table-column>
    </el-table>
  </div>
</template>
<style>
</style>
<script>
export default {
  props: ["typeUser"],
  components: {},
  data() {
    return {
      customers: [],
      selected_id: null
    };
  },
  async created() {},
  methods: {}
};
</script>
