import Vue from 'vue'
import router from './routes'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'
import '@mdi/font/css/materialdesignicons.css'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
require('./bootstrap');

//prototypes
Vue.prototype.$axios = axios
Vue.prototype.$auth = window.auth

Vue.use(Vuetify)
Vue.use(VueSweetalert2);

Vue.component('App', require('./App.vue').default)

const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify(),
})
