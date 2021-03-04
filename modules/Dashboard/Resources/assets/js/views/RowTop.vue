<template>
  <div class="row">
    <div class="col-6 col-md-2">
      <div class="card">
        <div class="card-body">
        <div class="card-title">Cantidad <br />CPE Emitidos</div>
          <span>387</span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card">
        <div class="card-body">
        <div class="card-title">Fec venc del <br />Certificado</div>
          <span>2021-02-01</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.card {
  box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
  border-radius: 5px;
}
.card-body {
  font-size: 1rem;
  font-weight: 600;
}
.card-title {
  font-weight: 500;
  font-size: 14px;
  min-height: 60px;
}
html.dark {
  .card {
    color: #5bc0de;
  }
}
</style>
