import Vue from 'vue'
import VueRouter from 'vue-router';

import Home from './views/Home';
import Login from './views/Login';
import FacilityNew from "./views/FacilityNew";
import FacilityOverview from "./views/FacilityOverview";
import FacilityShow from "./views/FacilityShow";
import ErrorPage from './views/ErrorPage';
import FacilityGuests from "./views/FacilityGuests";
import FacilityEdit from "./views/FacilityEdit";
import UserEdit from "./views/UserEdit";
import FacilityDataProtection from "./views/FacilityDataProtection";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        }, {
            path: '/facility',
            name: 'facility.overview',
            component: FacilityOverview
        }, {
            path: '/facility/new',
            name: 'facility.new',
            component: FacilityNew
        }, {
            path: '/facility/:id',
            name: 'facility.show',
            component: FacilityShow
        }, {
            path: '/facility/:id/edit',
            name: 'facility.edit',
            component: FacilityEdit
        }, {
            path: '/facility/:id/guests',
            name: 'facility.guests',
            component: FacilityGuests
        }, {
            path: '/facility/:id/data-protection',
            name: 'facility.dataProtection',
            component: FacilityDataProtection
        }, {
            path: '/account/me',
            name: 'user.edit',
            component: UserEdit
        }, {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                auth: false
            }
        }, {
            path: '/password/reset',
            name: 'password.reset',
            component: () => import(/* webpackChunkName: "AuthResetPassword" */ './views/Auth/AuthResetPassword.vue'),
            meta: {
                auth: false
            }
        }, {
            path: '/password/reset/:token',
            name: 'password.reset.form',
            component: () => import(/* webpackChunkName: "AuthResetPassword" */ './views/Auth/AuthResetForm.vue'),
            meta: {
                auth: false
            }
        }, {
            path: '/register',
            name: 'register',
            component: () => import(/* webpackChunkName: "AuthResetPassword" */ './views/Auth/AuthRegister.vue'),
            meta: {
                auth: false
            }
        }, {
            path: '*',
            name: '404',
            component: ErrorPage
        }
    ],
});

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('token') != null;

    if (!to.matched.some(record => record.meta.auth == false) && !loggedIn) {
        next('login')
        return
    }

    next();
})

export default router;
