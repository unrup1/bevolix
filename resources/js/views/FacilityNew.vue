<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Neue Einrichtung</h2>
                <div class="alert alert-warning shadow-sm" v-if="$route.query.redirect">
                    <strong>Herzlich Willkommen bei Bevolix!</strong><br>
                    Bitte fügen Sie zuerst eine Einrichtung zu ihrem Account hinzu. Danach können Sie den QR Code generieren.
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form @submit.prevent="onSubmit">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <small class="text-muted">Pflichtfeld</small></label>
                                <input id="name" type="text" class="form-control" v-bind:class="{ 'is-invalid': invalid['name'] }" name="name" v-model="facility.name" required autofocus>
                                <span v:if="invalid['name']" class="invalid-feedback" role="alert">
                                    <strong>{{ errorMessage }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label v-on:click="$refs.description.quill.focus()" class="form-label">Beschreibung <small class="text-muted">Optional</small></label>
                                <quill-editor
                                    ref="description"
                                    v-model="facility.description"
                                    :options="{ placeholder: 'Optionale Beschreibung unter dem Namen' }"
                                    v-bind:class="{ 'is-invalid': invalid['description'] }"
                                ></quill-editor>
                                <span v:if="invalid['description']" class="invalid-feedback" role="alert">
                                    <strong>{{ errorMessage }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Erstellen
                                </button>

                                <router-link v-if="!$route.query.redirect" class="btn btn-link text-danger" :to="{ name: 'home' }">
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
import axios from "axios";

export default {
    data() {
        return {
            facility: {
                name: '',
                description: '',
            },
            invalid: [],
            errorMessage: ''
        }
    },
    methods: {
        onSubmit() {
            return axios
                .post('/facility', this.facility)
                .then(({ data }) => {
                    this.$router.push({ name: 'facility.show', params: { id: data.id } })
                })
        }
    }
}
</script>
