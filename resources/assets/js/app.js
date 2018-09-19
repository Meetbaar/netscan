
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


window.Vue = require('vue');
import VueRouter from 'vue-router'
import AtComponents from 'at-ui'
import 'at-ui-style'    // Import CSS
import axios from 'axios';
import VueAxios from 'vue-axios';
import Auth from "./packages/auth/Auth.js"
import askApp from "./packages/askApp/askApp"

import VueTimeago from 'vue-timeago'







import StartPoint from './components/StartPoint'
import NotFoundView from './components/error'
import Login from './functions/auth/login'
import UserMainPage from "./components/dashboard/Users/users"

import SubnetMainPage from "./components/dashboard/Subnets/subnets"
import IPAdressPage from "./components/dashboard/Subnets/adresses"
import DashboardPage from "./components/dashboard/Dashboard/Dashboard"



import MainLayout from "./components/dashboard/Layouts/Main"
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('startpoint', require('./components/StartPoint.vue'));
Vue.use(VueRouter);
Vue.use(require('vue-moment'));

const routes = [
    { path:'/', component: StartPoint,name: "startpoint",
        meta: {
            auth: false
        }
    },
    { path:'/login', component: Login,name: "login",
        meta: {
            auth: false,
            title: "Please Login"
        },
        title: "Please login"
    },
    { path: '/dashboard', component: MainLayout, children: [
            {
                path: '/',
                component: DashboardPage,
                name: "dashboard",
                meta: {
                    title: "Dashboard"
                }
            },

            {
                path: '/users',
                component: UserMainPage,
                name: "users",
                meta: {
                    title: "Users"
                }
            },
            {
                path: '/subnets',
                component: SubnetMainPage,
                name: "subnets",
                meta: {
                    title: "Subnets"
                }
            },
            {
                path: '/subnets/:id',
                component: IPAdressPage,
                name: "subnetIP",
                meta: {
                    title: "IP-Adresses"
                }
            },
        ],
        meta: {
            auth: true
        }
    }, {
        // not found handler
        path: '*',
        component: NotFoundView
    }
]
Vue.use(VueAxios, axios);
Vue.use(AtComponents);
Vue.use(askApp);
Vue.use(Auth);
const router = new VueRouter({routes});
axios.defaults.baseURL = 'http://10.96.12.6/';

Vue.router = router;

router.beforeEach((to, from, next) => {
    document.title = to.meta.title

    if(to.matched.some(record => record.meta.auth)) {
        Vue.askApp.makeTestRequest();


        if(Vue.auth.isAuthenticated()) {
            next()
        } else {
            next({
                path: "login"
            })
        }
    }
    if(!to.matched.some(record => record.meta.auth)) {
        if(Vue.auth.isAuthenticated()) {
            next({
                path: "dashboard"
            })
        } else {
            next()
        }
    }
});





const vue = new Vue({
    el: '#app',
    router,
    data() {
        return {
            baseURL: "localhost:8000/api/"
        }
    }

});
