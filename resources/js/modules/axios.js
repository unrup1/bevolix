import Vue from 'vue'
import axios from 'axios'

// change http with axios
Vue.prototype.$http = axios

// config
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
axios.defaults.baseURL = window.location.origin + '/api';
