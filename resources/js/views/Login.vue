<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-success shadow-sm" v-if="$route.query.logout">
                    Sie wurden erfolgreich ausgeloggt.
                </div>
                <div class="alert alert-warning shadow-sm" v-if="$route.query.expired">
                    Ihre Sitzung ist abgelaufen.
                </div>
                <div class="alert alert-success shadow-sm" v-if="$route.query.delete">
                    Ihr Account wurde erfolgreich gelöscht.
                </div>
                <h1>Login</h1>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form @submit.prevent="login">

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-bind:class="{ 'is-invalid': invalid }" name="email" v-model="email" required autocomplete="email" autofocus>

                                    <span v:if="invalid" class="invalid-feedback" role="alert">
                                        {{ errorMessage }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-bind:class="{ 'is-invalid': invalid }" name="password" v-model="password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <router-link class="btn btn-link" :to="{ name: 'password.reset', props: { email: email } }">
                                        Passwort vergessen?
                                    </router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            email: '',
            password: '',
            invalid: false,
            errorMessage: ''
        }
    },
    created() {
        if (this.$store.getters.isLogged) {
            this.$router.push({ name: 'home' })
        }
    },
    methods: {
        login () {
            this.invalid = false;
            this.$store
                .dispatch('login', {
                    email: this.email,
                    password: this.password
                })
                .then(() => {
                    this.$router.push({ name: 'home' })
                })
                .catch((err) => {
                    this.invalid = true;
                    this.errorMessage = 'Das Passwort oder die E-Mail sind falsch.'
                })
        }
    }
}
</script>
