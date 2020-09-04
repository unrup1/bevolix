<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-12 col-sm-9">
                        <h5 class="text-muted">Einrichtung</h5>
                        <h2>{{ facility.name }}</h2>
                    </div>
                    <div class="d-none d-sm-flex col-3 justify-content-end align-items-center">
                        <router-link class="btn btn-outline-secondary" :to="{ name: 'facility.edit', params: { id: facility.id } }">Bearbeiten</router-link>
                    </div>
                </div>
                <div class="alert alert-success shadow-sm" v-if="$route.query.saved">
                    Die Daten wurden erfolgreich gespeichert.
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3>Link zur Eingabe</h3>
                        <input readonly type="text" v-bind:value="getFormUrl()" ref="readUrl" @focus="$refs.readUrl.setSelectionRange(0, $refs.readUrl.value.length)" class="w-100" />
                        <a class="btn btn-outline-secondary mt-3" v-bind:href="getFormUrl()" target="_blank">
                            Eingabe öffnen <ion-icon name="open-outline"></ion-icon>
                        </a>
                        <hr />
                        <h3>QR Code</h3>
                        <div class="d-flex">
                            <div class="qr-code-no-border">
                                <img :src="'/facility/' + facility.id + '/qr/png-150'" alt="QR Code als PNG">
                            </div>
                            <div class="ml-3 d-flex flex-column">
                                <a :href="'/facility/' + facility.id + '/qr/png-150'" class="btn btn-outline-primary" :download="getImageTitle('png')"><ion-icon name="download-outline"></ion-icon> Download PNG (150x150)</a>
                                <a :href="'/facility/' + facility.id + '/qr/png-300'" class="btn btn-outline-primary mt-3" :download="getImageTitle('png')"><ion-icon name="download-outline"></ion-icon> Download PNG (300x300)</a>
                                <a :href="'/facility/' + facility.id + '/qr/svg'" class="btn btn-outline-primary mt-3" :download="getImageTitle('svg')"><ion-icon name="download-outline"></ion-icon> Download SVG</a>
                            </div>
                        </div>
                        <hr />
                        <h3>Datenschutzerklärung</h3>
                        <div v-if="hasFile" class="mb-2">
                            <h5 class="text-success"><ion-icon name="checkmark-circle-outline"></ion-icon> Datenschutzerklärung vorhanden</h5>
                            <a :href="`/c/${facility.corona_hash}/data-protection`" :download="`Datenschutzerklärung ${facility.name}.pdf`" class="btn btn-outline-primary">
                                <ion-icon name="download-outline"></ion-icon> PDF ansehen
                            </a>
                            <router-link class="btn btn-link" :to="{ name: 'facility.dataProtection', params: { id: facility.id } }">
                                Datenschutzerkläung hochladen oder erstellen
                            </router-link>
                        </div>
                        <div v-else>
                            <h5 class="text-danger mb-2"><ion-icon name="close-circle-outline" size="medium"></ion-icon> Keine Datenschutzerklärung vorhanden</h5>
                            <router-link class="btn btn-outline-primary" :to="{ name: 'facility.dataProtection', params: { id: facility.id } }">
                                Datenschutzerkläung hochladen oder erstellen
                            </router-link>
                        </div>
                        <hr />
                        <h3>Daten</h3>
                        <router-link class="btn btn-outline-primary" :to="{ name: 'facility.guests', params: { id: facility.id } }">
                            <ion-icon name="people-outline"></ion-icon> Daten ansehen
                        </router-link>
                        <router-link class="btn btn-outline-primary" :to="{ name: 'facility.guests', params: { id: facility.id }, query: { open_export: true} }">
                            <ion-icon name="download-outline"></ion-icon> Daten exportieren
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data () {
        return {
            facility: {},
            appUrl: document.querySelector('meta[name="appUrl"]').content,
            file: null,
            hasFile: false
        }
    },
    beforeRouteEnter (to, from, next) {
        axios.get('/facility/' + to.params.id).then((response) => {
            next((vm) => { vm.setData(response) });
        })
    },
    created() {
        this.$http.get('/facility/' + this.$route.params.id + '/data-protection').then(({ data }) => {
            this.hasFile = data.has_file;
        });
    },
    // when route changes and this component is already rendered,
    // the logic will be slightly different.
    /*beforeRouteUpdate (to, from, next) {
        this.data = [];
        this.$http.get('/facility').then((response) => {
            this.setData(response)
        })
    },*/
    methods: {
        setData (response) {
            this.facility = response.data;
        },
        getFormUrl() {
            return `${this.$data.appUrl}/c/${this.$data.facility.corona_hash}`;
        },
        getImageTitle(suffix) {
            return this.$data.facility.name + ' QR Code.' + suffix;
        }
    }
}
</script>
