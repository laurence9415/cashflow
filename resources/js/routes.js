import Vue from 'vue'
import VueRouter from 'vue-router'

import Dashboard from './pages/Dashboard.vue'
import Cashflow from './pages/Cashflow.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {path: '/dashboard', component: Dashboard},
        {path: '/cashflow', component: Cashflow}
    ]
})

export default router