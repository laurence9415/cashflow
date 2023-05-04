import Vue from 'vue'
import VueRouter from 'vue-router'

import Dashboard from './pages/Dashboard.vue'
import Cashflow from './pages/Cashflow.vue'
import Business from './pages/Business.vue'
import BusinessExpense from './pages/Business/Expense.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {path: '/dashboard', component: Dashboard},
        {path: '/cashflow', component: Cashflow},
        {
            path: '/businesses', 
            component: Business,
            children: [
                {
                    path: ':businessId/expenses',
                    component: BusinessExpense,
                    props: true
                }
            ]
        },
    ]
})

export default router