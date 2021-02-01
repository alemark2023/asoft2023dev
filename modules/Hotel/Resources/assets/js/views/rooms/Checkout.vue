<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active"><span>RECEPCIÓN</span></li>
      </ol>
      <div class="right-wrapper pull-right">
        <div class="btn-group flex-wrap">
          <button
            type="button"
            class="btn btn-custom btn-sm mt-2 mr-2"
            @click="onGotoBack"
          >
            <i class="fa fa-arrow-left"></i> Atras
          </button>
        </div>
      </div>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">{{ title }}</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-4">
            <ul class="list-group">
              <li class="list-group-item active">Habitación</li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Habitación:</span>
                <strong>{{ this.rent.room.name }}</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Tipo:</span>
                <strong>{{ this.rent.room.category.description }}</strong>
              </li>
            </ul>
          </div>
          <div class="col-12 col-md-4">
            <ul class="list-group">
              <li class="list-group-item active">Cliente</li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Nombres:</span>
                <strong>{{ this.rent.customer.name }}</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Email:</span>
                <strong>{{ this.rent.customer.number }}</strong>
              </li>
            </ul>
          </div>
          <div class="col-12 col-md-4">
            <ul class="list-group">
              <li class="list-group-item active">Entrada/Salida</li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Fecha/Hora Entrada:</span>
                <strong>{{ this.rent.input_date | toDate }} - {{ this.rent.input_time | toTime }}</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Fecha/Hora Salida:</span>
                <strong>{{ this.rent.output_date | toDate }} - {{ this.rent.output_time | toTime }}</strong>
              </li>
            </ul>
          </div>
          <div class="col-12">
              <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="5">Costo del alojamiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#</td>
                            <td>Costo por tarifa</td>
                            <td>Cant. noches</td>
                            <td>Carga por salir tarde</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr></tr>
                    </tbody>
                </table>
              </div>
          </div>
          <div class="col-12 pt-3">
              <el-button :loading="loading" :disabled="loading" type="primary">
                  <i class="fa fa-save"></i>
                  <span class="ml-2">Guardar y Generar Comprobante</span>
              </el-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    rent: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      title: "",
    };
  },
  mounted() {
    this.title = `Checkout: Habitación ${this.rent.room.name}`;
  },
  methods: {
    onGotoBack() {
      window.location.href = "/hotels/reception";
    },
  },
};
</script>
