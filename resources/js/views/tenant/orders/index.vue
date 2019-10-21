<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard">
          <i class="fas fa-tachometer-alt"></i>
        </a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active">
          <span>Pedidos</span>
        </li>
      </ol>
      <div class="right-wrapper pull-right"></div>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">Listado de Pedidos Tienda Virtual</h3>
      </div>
      <div class="card-body">
        <data-table :resource="resource">
          <tr slot="heading" width="100%">
            <th>#</th>
            <th>External ID</th>
            <th>Cliente</th>
            <th class="text-center" >Detalle Productos</th>
            <th>Total</th>
            <th>Fecha Emision</th>
            <th>Medio Pago</th>
            <th>Referencia Comprobante</th>
          </tr>
          <tr></tr>
          <tr slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.external_id }}</td>
            <td>{{ row.customer }}</td>
            <td class="text-center">
              <template>
                <el-popover placement="right" width="400" trigger="click">
                  <el-table  style="width: 100%" :data="row.items">
                    <el-table-column width="150" property="name" label="Nombre"></el-table-column>
                    <el-table-column width="90" property="cantidad" label="Cantidad"></el-table-column>
                    <el-table-column width="150" label="Precio">
                      <template slot-scope="scope">
                        <span>{{ Number( scope.row.sale_unit_price).toFixed(2)}}</span>
                      </template>
                    </el-table-column>
                  </el-table>
                  <el-button slot="reference" icon="el-icon-zoom-in"></el-button>
                </el-popover>
              </template>
            </td>
            <td>{{row.total}}</td>
            <td>{{row.created_at}}</td>
            <td>{{row.reference_payment}}</td>
            <td>{{row.document_external_id}}</td>
          </tr>
        </data-table>
      </div>
    </div>
  </div>
</template>
<script>
import DataTable from "../../../components/DataTable.vue";

export default {
  props: [],

  components: { DataTable },
  data() {
    return {
      showDialog: false,
      showImportDialog: false,
      showImageDetail: false,
      resource: "orders",
      recordId: null
    };
  },
  created() {},
  methods: {}
};
</script>
