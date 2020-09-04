import Vue from 'vue'
import axios from 'axios'
import DatePicker from 'v-calendar/lib/components/date-picker.umd'
import './modules/axios';
import './modules/quill';
import './modules/vee-validation'; // Form Validation
import App from './views/App';
import store from './store';
import router from './router';
/* Filter */
import './filter/dateTime';

// Register datepicker
Vue.component('v-date-picker', DatePicker)

/* Ionicons */
Vue.config.ignoredElements = [/^ion-/]

const vm = new Vue({
    el: '#app',
    store,
    router,
    created () {
        if (localStorage.getItem('token') && document.cookie.indexOf('XSRF-TOKEN') > -1) {
            this.$store.commit('loadAllUserData')
        } else {
            this.$http.get({ url: 'sanctum/csrf-cookie', baseURL: window.location.origin }).then(response => {
                // nothing to do
            });
        }
    },
    components: { App }
});

axios.interceptors.response.use(response => {
    return response;
}, error => {
    // only important when logged in
    if (vm.$store.getters.isLogged) {
        switch (error.response.status) {
            case 401:
                vm.$store.dispatch('logout');
                vm.$router.push({ name: 'login', query: { expired: true, backTo: vm.$route.fullPath }});
                break;
            case 404:
                vm.$router.push({ name: '404', query: { notFound: true }});
                break;
        }
    }

    throw error;
});

