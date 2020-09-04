<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Meine Account</h2>
                <div class="alert alert-success shadow-sm" ref="alertSuccess" v-show="successMessage != null">
                    {{ successMessage }}
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3>Benutzerdaten</h3>
                        <form @submit.prevent="onUserSubmit">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" type="text" class="form-control" name="name" v-model="userData.name" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">E-Mail</label>
                                <input id="email" type="text" class="form-control" name="email" v-model="userData.email" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-primary">
                                    Speichern
                                </button>
                            </div>
                        </form>

                        <hr>
                        <h3>Passwort</h3>
                        <form @submit.prevent="onPasswordSubmit">
                            <div class="form-group">
                                <label for="password" class="form-label">Passwort</label>
                                <input id="password" type="password" class="form-control" name="password" v-model="password" required autocomplete="new-password">
                            </div>
                            <div class="form-group">
                                <label for="password_repeat" class="form-label">Wiederholung Passwort</label>
                                <input id="password_repeat" type="password" class="form-control" name="password_repeat" v-model="password_repeat" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-primary">
                                    Speichern
                                </button>
                            </div>
                        </form>

                        <hr>
                        <h3>Account löschen</h3>
                        <p>Wenn Sie den Account löschen, werden alle Daten gelöscht. Eine Wiederherstellung ist nicht möglich.</p>
                        <button type="button" class="btn btn-outline-danger" @click="openDeleteAccountModal">
                            Account löschen
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" :class="{'show': showModal, 'd-block': showModal }" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Account löschen</h5>
                        <button type="button" @click="closeDeleteAccountModal" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Wenn Sie den Account löschen, werden alle Daten gelöscht. Eine Wiederherstellung ist nicht möglich.<br>
                        Möchten Sie das wirklich?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-danger" @click="deleteAccount">Account löschen</button>
                        <button type="button" class="btn btn-primary" @click="closeDeleteAccountModal">Abbrechen</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModal" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            userData: {
                name: this.$store.state.user.name,
                email: this.$store.state.user.email,
            },
            password_repeat: null,
            password: null,
            successMessage: null,
            showModal: false
        }
    },
    methods: {
        setSuccessMessage(text) {
            this.successMessage = text;

            // after rendering scrollTo alert
            setTimeout(() => {
                this.$refs.alertSuccess.scrollIntoView(); // .scrollTop = 0;
            }, 0)

            // Remove alert after time
            setTimeout(() => {
                if (this.successMessage == text) {
                    this.successMessage = null;
                }
            }, 5000)
        },
        onUserSubmit() {
            return this.$http
                .post('/user/' + this.$store.state.user.id, this.userData)
                .then(({ data }) => {
                    this.setSuccessMessage('Daten erfolgreich gespeichert');
                    this.$store.commit('setUserData', data);
                }, err => {
                    console.log(err)
                })
        },
        onPasswordSubmit() {
            if (this.password != this.password_repeat) {
                // TODO show error for form
                return;
            }

            return this.$http
                .post('/user/' + this.$store.state.user.id + '/password', { password: this.password })
                .then((response) => {
                    this.setSuccessMessage('Passwort erfolgreich geändert');
                })
        },
        openDeleteAccountModal() {
            this.showModal = true;
            document.body.className += ' modal-open';
        },
        closeDeleteAccountModal() {
            this.showModal = false;
            document.body.className = document.body.className.replace(' modal-open', '');
        },
        deleteAccount() {
            return this.$http
                .delete('/user/' + this.$store.state.user.id)
                .then(({ data }) => {
                    this.$store.dispatch('logout');
                    this.$router.push({ name: 'login', query: { delete: true }});
                })
        }
    }
}
</script>
