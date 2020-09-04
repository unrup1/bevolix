import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        token: null,
        flash: {}
    },

    mutations: {
        setUserData (state, userData) {
            state.user = userData
            localStorage.setItem('user', JSON.stringify(userData))
        },

        setToken(state, token) {
            state.token = token;
            localStorage.setItem('token', token)
            axios.defaults.headers.common.Authorization = `Bearer ${token}`
        },

        clearAllUserData () {
            this.state.user = null;
            this.state.token = null;
            localStorage.removeItem('user')
            localStorage.removeItem('token')
            axios.defaults.headers.common.Authorization = null;
        },

        loadAllUserData(state) {
            const userInfo = localStorage.getItem('user')
            state.user = JSON.parse(userInfo)

            this.commit('setToken', localStorage.getItem('token'));
        },

        flash(state, name, value) {
            state.flash[name] = value;
        }
    },

    actions: {
        login ({ commit }, credentials) {
            return axios
                .post('/login', credentials)
                .then(({ data }) => {
                    commit('setUserData', data.user)
                    commit('setToken', data.token)
                })
        },

        logout ({ commit }) {
            this.$http.post('/logout').then(response => {
                // nothing to do
            });
            commit('clearAllUserData');
        },

        setFlash({ commit }, name,  value) {
            commit('flash', name, value);
        },

        getFlash({ commit }, name) {
            const value = this.state.flash[name] ? this.state.flash[name] : null;
            commit('flash', name, null);
            return value;
        }
    },

    getters : {
        isLogged: state => !!state.user
    }
})
