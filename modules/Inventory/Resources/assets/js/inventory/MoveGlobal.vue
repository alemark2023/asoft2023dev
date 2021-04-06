<template>
  <el-dialog
    title="Trasladar multiples productos entre almacenes"
    width="80%"
    :visible="show"
    @open="create"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    append-to-body
    :show-close="false"
  >
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Almacén local</th>
          <th>Can. actual</th>
          <th>Almacén final</th>
          <th>Motivo de traslado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in products" :key="row.id">
          <td>{{ row.item_fulldescription }}</td>
          <td>{{ row.warehouse_description }}</td>
          <td>{{ row.stock }}</td>
          <td>
            <el-select v-model="row.warehouse_new_id">
              <el-option
                v-for="option in warehouses"
                :key="option.id"
                :value="option.id"
                :label="option.description"
              ></el-option>
            </el-select>
          </td>
          <td>
            <el-input v-model="row.quantity" type="number"></el-input>
          </td>
          <td>
            <el-input v-model="row.reason" maxlength="100"></el-input>
          </td>
        </tr>
      </tbody>
    </table>
  </el-dialog>
</template>
<script>
export default {
  props: {
    show: {
      type: Boolean,
      required: false,
      default: false,
    },
    products: {
      type: Array,
      required: false,
      default: [],
    },
  },
  data() {
    return {
      errors: {},
      resource: "inventory",
      form: {
        warehouse_new_id: "",
      },
      warehouses: [],
    };
  },
  created() {
    this.$http.get(`/${this.resource}/tables`).then((response) => {
      this.warehouses = response.data.warehouses;
    });
  },
  methods: {
    create() {
      console.log(this.products);
      const params = {
        ids: this.products.map((p) => p.id),
      };
      console.log(params);
      //   this.$http
      //     .get(`/${this.resource}/record/multiple`)
      //     .then((response) => {
      //       this.form = response.data.data;
      //       this.form.lots = Object.values(response.data.data.lots);
      //     });
    },
  },
};
</script>
