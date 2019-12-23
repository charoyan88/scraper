require('./bootstrap');
import 'es6-promise/auto'
import Vue from 'vue'
import VueRouter from 'vue-router'
import Index from './Index'
import router from './router'
// Set Vue globally
window.Vue = Vue;
// Set Vue router
Vue.router = router;
Vue.use(VueRouter);

// Load Index
Vue.component('index', Index);

const app = new Vue({
    el: '#app',
    router,
});