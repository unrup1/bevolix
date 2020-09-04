import Vue from 'vue'
import './modules/axios';
import './modules/vee-validation';
import CoronaApp from "./views/Corona/CoronaApp.vue";

var vm = new Vue({
    el: '#app',
    components: { CoronaApp }
});

