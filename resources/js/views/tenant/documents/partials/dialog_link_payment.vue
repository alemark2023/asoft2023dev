<template>
  <div class="pt-2">
    <el-button type="primary" @click="visibleDialog = true">Link de pago</el-button>
    <el-dialog :title="titleDialog" :visible="visibleDialog" class="" @close="visibleDialog = false">
      <el-tabs v-model="activeTab" @tab-click="handleClick">
        <el-tab-pane label="Yape" name="first">
          <div class="row">
            <div class="col-md-7 bg-yape text-center pr-0">
              <img src="/logo/yape-logo.png" class="img-fluid" alt="Yape">
              <div class="payment-links-yape">
                <el-button type="primary" class="mt-2" icon="el-icon-share">Copiar Link</el-button>
                <el-button type="primary" class="mt-2" icon="fas fa-envelope fa-fw">Enviar Correo</el-button>
                <el-button type="primary" class="my-2" icon="fab fa-whatsapp fa-fw">Enviar Whatsapp</el-button>
              </div>
            </div>
            <div class="col-md-5 pt-2">
              <el-button type="primary">Adjuntar pago</el-button>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="Mercado Pago" name="second">
          <div class="row">
            <div class="col-md-7 bg-mercadopago text-center pr-0">
              <img src="/logo/logo_mercadopago.jpg" class="img-fluid" alt="Yape">
              <div class="payment-links-mp">
                <el-button type="primary" class="mt-2" icon="el-icon-share">Copiar Link</el-button>
                <el-button type="primary" class="mt-2" icon="fas fa-envelope fa-fw">Enviar Correo</el-button>
                <el-button type="primary" class="my-2" icon="fab fa-whatsapp fa-fw">Enviar Whatsapp</el-button>
              </div>
            </div>
            <div class="col-md-5 pt-2">
              <el-button type="primary">Consultar estado</el-button>
            </div>
          </div>
        </el-tab-pane>
      </el-tabs>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data(){
    return {
      visibleDialog: false,
      titleDialog: 'Link de Pago',
      activeTab: 'first'
    }
  }
}
</script>

<style>
.el-dialog__body {
  padding-top: 0px;
}
.payment-links-yape .el-button--primary {
  background-color: #10cbb4;
  border-color: #10cbb4;
}
.bg-yape {
  background: #5e186c;
}
</style>