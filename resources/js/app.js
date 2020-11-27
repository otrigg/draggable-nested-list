require('./bootstrap');
window.Vue = require('vue');

import App from './App.vue';
import NotifyPlugin from 'vue-easy-notify';
import 'vue-easy-notify/dist/vue-easy-notify.css';

Vue.use(NotifyPlugin);

const app = new Vue({
    el: '#app',
    render: h => h(App)
});
