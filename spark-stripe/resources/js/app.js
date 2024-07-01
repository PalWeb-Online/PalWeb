window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createInertiaApp } from '@inertiajs/vue2';
import BaseMixin from './Mixins/Base';
import Vue from 'vue'

Vue.mixin(BaseMixin);

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    Vue.use(plugin)

    new Vue({
      render: h => h(App, props)
    }).$mount(el)
  }
})
