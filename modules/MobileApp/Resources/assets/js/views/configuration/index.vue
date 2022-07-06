<template>
  <div>
    <div class="page-header pr-0">
      <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
      <ol class="breadcrumbs">
        <li class="active"><span> App 2.0</span></li>
        <li class="active"><span> Configuración</span></li>
      </ol>
      <div class="right-wrapper pull-right"></div>
    </div>
    <div class="row">
      <div class="col-md-7">
        <div class="card">
          <div class="card-body">
            <h4>Configuración gráfica</h4>
            <el-form ref="form" :model="form" label-width="145px" size="mini">
              <el-form-item label="Color de tema" class="mb-0">
                <el-radio-group class="pt-1" v-model="form.skin" @change="changeThemePrimary(form.skin)">
                  <el-radio :label="1">Azul</el-radio>
                  <el-radio :label="2">Rojo</el-radio>
                  <el-radio :label="3">Oscuro</el-radio>
                </el-radio-group>
              </el-form-item>
              <!-- no funcionales desde aqui -->
              <el-form-item label="Color de cajas" class="mb-0">
                <el-radio-group class="pt-1" v-model="form.cards" @change="changeThemeCards(form.cards)">
                  <el-radio :label="1">Multicolor</el-radio>
                  <el-radio :label="2" >Unicolor</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="Tipo de operación" class="mb-0">
                <el-radio-group class="pt-1" v-model="form.operation_type">
                  <el-radio :label="1">Facturación</el-radio>
                  <el-radio :label="2" >POS</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="Encabezado" class="mb-0">
                <el-radio-group class="pt-1" v-model="form.header">
                  <el-radio :label="1">Plano</el-radio>
                  <el-radio :label="2" >Ondulado</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="Permisos" class="mb-0">
              </el-form-item>
            </el-form>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <iframe :src="path_app" frameborder="0" height="600" ref="appIframe" style="z-index: 999;"></iframe>
      </div>
    </div>
</div>
</template>

<style scoped>
</style>

<script>
export default {
  data() {
    return {
      form: {
        skin: 1,
        cards: 1,
        operation_type: 1,
        permissions: {},
        header: 1,
      },
      domain: window.location.origin,
      path_app: window.location.origin + '/live-app',
    }
  },
  methods: {
    changeThemePrimary(optionSkin) {
      let iframe = this.$refs.appIframe
      let doc = iframe.contentDocument

      switch (optionSkin) {
        case 2:
          doc.head.querySelectorAll('[href="' + this.domain +'/liveapp/assets/skin-dark.css"]').forEach(el => el.remove())
          doc.head.innerHTML = doc.head.innerHTML + '<link href="' + this.domain +'/liveapp/assets/skin-red.css" rel="stylesheet">'
          break;
        case 3:
          doc.head.querySelectorAll('[href="' + this.domain +'/liveapp/assets/skin-red.css"]').forEach(el => el.remove())
          doc.head.innerHTML = doc.head.innerHTML + '<link href="' + this.domain +'/liveapp/assets/skin-dark.css" rel="stylesheet">'
          break;
        default:
          doc.head.querySelectorAll('[href="' + this.domain +'/liveapp/assets/skin-dark.css"]').forEach(el => el.remove())
          doc.head.querySelectorAll('[href="' + this.domain +'/liveapp/assets/skin-red.css"]').forEach(el => el.remove())
          break;
      }
    },
    changeThemeCards(optionCard){
      let iframe = this.$refs.appIframe
      let doc = iframe.contentDocument

      switch (optionCard) {
        case 2:
          doc.head.innerHTML = doc.head.innerHTML + '<link href="' + this.domain +'/liveapp/assets/cards.css" rel="stylesheet">'
          break;
        default:
          doc.head.querySelectorAll('[href="' + this.domain +'/liveapp/assets/cards.css"]').forEach(el => el.remove())
          break;
      }
    }
  }
}
</script>