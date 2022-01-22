import Vue from 'vue'
import router from './routes'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'
import '@mdi/font/css/materialdesignicons.css'

require('./bootstrap');

//prototypes
Vue.prototype.$axios = axios
Vue.prototype.$auth = window.auth

Vue.use(Vuetify)
Vue.component('App', require('./App.vue').default)

const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify(),
})
