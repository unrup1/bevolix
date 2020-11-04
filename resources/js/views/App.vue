<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <router-link class="navbar-brand" to="/">
                    Bevolix
                </router-link>
                <button  v-if="isLogged" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul v-if="isLogged" class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <router-link :to="{ name: 'home' }" class="nav-link">Dashboard</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'facility.overview' }" class="nav-link">Einrichtungen</router-link>
                        </li>
                        <!-- <li class="nav-item">
                            <router-link :to="{ name: '' }" class="nav-link"></router-link>
                        </li> -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto" v-if="isLogged">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <router-link :to="{ name: 'user.edit' }" class="nav-link">{{ user.name }}</router-link>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-secondary nav-link btn-logout" @click="logout()">Logout</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto" v-else>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'login' }">Login</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'register' }">Registrieren</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div v-if="isLogged && user.email_verified_at === null" class="alert-warning alert shadow-sm text-center">
            Ihre E-Mail Adresse ist noch nicht verifiziert. Bitte gehen Sie in Ihr E-Mail Konto und klicken Sie auf den Aktivierungslink. <a class="link" @click="resendVerify()">Nochmal senden</a>
        </div>

        <main class="py-4">
            <router-view></router-view>
        </main>

        <div class="container">
            <div class="col text-center">
                <a href="https://github.com/unrup1/bevolix" target="_blank"><ion-icon name="logo-github"></ion-icon> Github</a>
                <a class="ml-3" href="/imprint">Impressum</a>
                <a class="ml-3" href="/privacy-policy">Datenschutzerklärung</a>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapState } from 'vuex';

export default {
    methods: {
        logout() {
            this.$store.dispatch('logout')
            this.$router.push({ name: 'login', query: { logout: true }});
        },
        resendVerify() {
            this.$http.post('email/resend').then(response => null);
        }
    },
    computed: {
        ...mapGetters(['isLogged']),
        ...mapState([
            'user'
        ])
    },
}
</script>
