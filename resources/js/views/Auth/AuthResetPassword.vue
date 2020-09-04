<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Passwort zurücksetzen</h1>
                <div class="alert alert-success shadow-sm" v-if="showAlert">
                    Es wurde eine E-Mail versendet, um ihr Passwort zurück zusetzen.
                </div>
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent>
                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input id="email" type="email" class="form-control" v-model="email" name="email" required autocomplete="email" autofocus>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" @click="sendPasswordReset" :disabled="showAlert">
                                    Absenden
                                </button>
                                <router-link :to="{ name: 'login' }" class="btn btn-link">
                                    Abbrechen
                                </router-link>
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
    data() {
        return {
            email: null,
            showAlert: false
        }
    },
    methods: {
        sendPasswordReset() {
            this.$http.post('/password/email', { email: this.email })
            .then(() => {
                this.showAlert = true;
            })
        }
    }
}
</script>
