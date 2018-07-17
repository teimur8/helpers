require('./bootstrap');

window.Vue = require('vue');

//Vue.use(VModal);


import VueWait from 'vue-wait';
Vue.use(VueWait);

window.events = new Vue();

//const app = new Vue({
//    el: '#app'
//});
