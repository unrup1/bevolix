<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Registrieren</h1>
                <div class="alert alert-success shadow-sm" v-if="showAlert">
                    <strong>Sie wurden erfolgreich registriert!</strong><br>
                    In {{ seconds }} Sekunden werden Sie zur Startseite weitergeleitet.
                </div>
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{ handleSubmit }">
                            <form @submit.prevent="handleSubmit(register)" novalidate>
                                <div class="form-group">
                                    <label for="name">Name <small class="text-muted">Pflichtfeld</small></label>
                                    <ValidationProvider name="Name" rules="required|max:255" v-slot="{ errors, classes }" :debounce="400">
                                        <input id="name" type="text" class="form-control" :class="classes" name="name" v-model="user.name" required autocomplete="name" autofocus>
                                        <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                    </ValidationProvider>
                                </div>

                                <div class="form-group">
                                    <label for="email">E-Mail <small class="text-muted">Pflichtfeld</small></label>
                                    <ValidationProvider name="E-Mail" rules="required|email|max:255" v-slot="{ errors, classes }" :debounce="400">
                                        <input id="email" type="email" class="form-control" :class="classes" name="email" v-model="user.email" required autocomplete="email">
                                        <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                    </ValidationProvider>
                                </div>

                                <div class="form-group">
                                    <label for="password">Passwort <small class="text-muted">Pflichtfeld</small></label>
                                    <ValidationProvider name="Passwort" rules="required|min:8" v-slot="{ errors, classes }" :debounce="400">
                                        <input id="password" type="password" class="form-control" :class="classes" v-model="user.password" required autocomplete="new-password">
                                        <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                        <div class="row">
                                            <div class="col-12 col-sm-3 pt-1">
                                                <span class="ml-1" v-if="scoreLabel != null" v-bind:class="scoreClass">{{ scoreLabel }}</span>
                                            </div>
                                            <div class="col pt-1">
                                                <password v-model="user.password" :strength-meter-only="true" @score="showScore" class="mx-0" strengthMeterClass="Password__strength-meter mb-2"/>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted ml-1">
                                            Das Passwort muss mindestens 8 Zeichen lang sein.
                                        </small>
                                    </ValidationProvider>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Registrieren</button>
                                </div>
                            </form>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Password from 'vue-password-strength-meter';

const scoreLabels = ['Schwach', 'Schwach', 'Mittel', 'Stark', 'Sehr Stark'];
const scoreClasses = ['danger', 'danger', 'warning', 'success', 'success'];

export default {
    components: { Password },
    data() {
        return {
            user: {},
            scoreLabel: null,
            scoreClass: null,
            seconds: 5,
            showAlert: false
        };
    },
    methods: {
        register() {
            this.$http.post('/register', this.user)
                .then(({ data }) => {
                    // save date for app
                    this.$store.commit('setUserData', data.user)
                    this.$store.commit('setToken', data.token)

                    // show alert and redirect to home
                    this.showAlert = true;
                    setInterval(() => {
                        this.seconds = this.seconds - 1;
                        if (this.seconds == 0) {
                            this.$router.push({ name: 'home' });
                        }
                    }, 1000); // 1000ms = 1s
                })
        },
        showScore(score) {
            this.scoreLabel = scoreLabels[score];
            this.scoreClass = 'text-' + scoreClasses[score];
        }
    }
}
</script>
