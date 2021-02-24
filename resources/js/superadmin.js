require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import ElementUI from 'element-ui'
import Axios from 'axios'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
locale.use(lang)

ElementUI.Select.computed.readonly = function () {
    const isIE = !this.$isServer && !Number.isNaN(Number(document.documentMode));
    return !(this.filterable || this.multiple || !isIE) && !this.visible;
};

export default ElementUI;

//Vue.use(ElementUI)
Vue.use(ElementUI, { size: 'small' })
Vue.prototype.$eventHub = new Vue()
Vue.prototype.$http = Axios

Vue.component('x-graph', require('./components/graph/src/Graph.vue'));
Vue.component('x-graph-line', require('./components/graph/src/GraphLine.vue'));

// System
Vue.component('system-clients-index', require('./views/system/clients/index.vue'));
Vue.component('system-clients-form', require('./views/system/clients/form.vue'));
Vue.component('system-users-form', require('./views/system/users/form.vue'));

Vue.component('system-certificate-index', require('./views/system/certificate/index.vue'));
Vue.component('system-companies-form', require('./views/system/companies/form.vue'));

Vue.component('system-accounting-index', require('@viewsModuleAccount/system/accounting/index.vue'));


Vue.component('system-plans-index', require('./views/system/plans/index.vue'));
Vue.component('system-plans-form', require('./views/system/plans/form.vue'));

Vue.component('x-input-service', require('./components/InputService.vue'));

//auto update
Vue.component('system-update', require('./views/system/update/index.vue'));

//auto update
Vue.component('system-backup', require('./views/system/backup/index.vue'));

//culqi
Vue.component('system-configuration-culqi', require('./views/system/configuration/culqi.vue'))

//token
Vue.component('system-configuration-token', require('./views/system/configuration/token_ruc_dni.vue'))

import mixins from './mixins/global';
Vue.mixin(mixins)
const app = new Vue({
    el: '#main-wrapper'
});
