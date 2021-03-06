<template>
  <div class="row">
    <div class="col-6 col-md-2">
      <div class="card card-dashboard">
        <div class="card-body">
        <div class="card-title">Cantidad <br />CPE Emitidos</div>
          <span>{{ total_cpe }}</span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2" v-if="company.certificate_due">
      <div class="card card-dashboard">
        <div class="card-body">
        <div class="card-title">Fec venc del <br />Certificado</div>
          <span>{{ company.certificate_due }}</span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card card-dashboard">
        <div class="card-body">
        <div class="card-title">Total <br />comprobantes</div>
          <span>{{ document_total_global }}</span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card card-dashboard">
        <div class="card-body">
        <div class="card-title">Total notas <br />de ventas</div>
          <span>{{ sale_note_total_global }}</span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card card-dashboard">
        <div class="card-body">
        <div class="card-title">Total <br />general</div>
          <span>{{ total }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: ['company'],
    data() {
        return {
            document_total_global: 0,
            total_cpe: 0,
            sale_note_total_global: 0,
            total: 0,
        }
    },
    mounted() {
        this.onFetchData();
    },
    methods: {
        onFetchData() {
            this.$http.get('/dashboard/global-data').then(response => {
                const data = response.data;
                this.document_total_global = data.document_total_global;
                this.total_cpe = data.total_cpe;
                this.sale_note_total_global = data.sale_note_total_global;
                this.total = parseFloat(this.document_total_global) + parseFloat(this.sale_note_total_global)
            })
        }
    }
};
</script>
