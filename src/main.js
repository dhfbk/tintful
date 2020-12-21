import Vue from 'vue'
import App from './App.vue'
import router from './router'
import './index.css'

Vue.config.productionTip = false

import Vuelidate from 'vuelidate'
import store from './store'
Vue.use(Vuelidate)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
