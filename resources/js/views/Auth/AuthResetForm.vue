<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3>Passwort zurücksetzen</h3>
                <div class="alert alert-success shadow-sm" ref="alert" v-if="alertShow">
                    Das Passwort wurde erfolgreich geändert. Sie können sich jetzt sofort <router-link :to="{ name: 'login' }" href="">einloggen</router-link>
                </div>
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{ handleSubmit}">
                            <form @submit.prevent="handleSubmit(resetPassword)" novalidate>
                                <p>Mit diesem Formular ändern Sie das Password für den Account <strong>{{ email }}</strong></p>
                                <FormInput name="Passwort" id="password" type="password" rules="required|min:8" v-model="resetData.password" autocomplete="new-password" autofocus="true" vid="true"></FormInput>
                                <FormInput name="Wiederholung Passwort" type="password" id="password-confirm" rules="required|min:8|confirmed:password" v-model="resetData['password_confirmation']"></FormInput>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Passwort neu setzen
                                    </button>
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
import FormInput from "../../component/FormInput";

export default {
    components: { FormInput },
    data() {
        return {
            token: this.$route.params.token,
            resetData: {
                email: this.$route.query.email,
                token: this.$route.params.token
            },
            alertShow: false,
            email: this.$route.query.email
        }
    },
    methods: {
        resetPassword () {
            return this.$http
                .post( `/password/reset`, this.resetData)
                .then(({ data }) => {
                    this.alertShow = true;
                })
        }
    }
}
</script>
