<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4 class="text-muted">{{ facility.name }}</h4>
                <h1>Datenschutzerklärung</h1>
                <div class="alert alert-success shadow-sm" ref="alert" v-if="alertShow">
                    {{ alertText }}
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div v-if="hasFile" class="mb-2">
                            <h5 class="text-success"><ion-icon name="checkmark-circle-outline"></ion-icon> Datenschutzerklärung vorhanden</h5>
                            <a :href="`/c/${facility.corona_hash}/data-protection`" :download="`Datenschutzerklärung ${facility.name}.pdf`" class="btn btn-outline-primary">
                                <ion-icon name="download-outline"></ion-icon> PDF ansehen
                            </a>
                            <a @click="removeFile" class="btn btn-link text-warning">Datenschutzerklärung entfernen</a>
                        </div>
                        <h5 class="text-danger mb-2" v-else><ion-icon name="close-circle-outline" size="medium"></ion-icon> Keine Datenschutzerklärung vorhanden</h5>
                        <h3>Datenschutzerklärung erstellen</h3>
                        <ValidationObserver v-slot="{ handleSubmit}">
                            <form @submit.prevent="handleSubmit(onGenerate)" novalidate>
                                <FormInput name="Verantwortlich Stelle" id="privacy_name" rules="required|max:255" v-model.trim="generate.privacy_name"></FormInput>
                                <FormInput name="Firmenname" id="facility_name" rules="required|max:255" v-model.trim="generate.facility_name"></FormInput>
                                <FormInput name="Firmenadresse" id="facility_address" rules="required|max:255" v-model.trim="generate.facility_address"></FormInput>
                                <FormInput name="Firmen PLZ und Ort" id="facility_location" rules="required|max:255" v-model.trim="generate.facility_location"></FormInput>
                                <FormInput name="Vertreten durch (Inhaber)" id="owner_name" rules="required|max:255" v-model.trim="generate.owner_name"></FormInput>
                                <FormInput name="Telefonnummer" id="telephone_number" rules="required|max:255" v-model.trim="generate.telephone_number"></FormInput>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Erstellen
                                    </button>

                                    <router-link class="btn btn-link" :to="{ name: 'facility.show', params: { id: facility.id } }">
                                        Zurück zu Übersicht
                                    </router-link>
                                </div>
                            </form>
                        </ValidationObserver>
                        <hr>
                        <h6 class="text-muted">Alternative</h6>
                        <h3>Datenschutzerklärung hochladen</h3>
                        <ValidationObserver  v-slot="{ handleSubmit }">
                            <form @submit.prevent="handleSubmit(onUpload)">
                                <div class="form-group">
                                    <ValidationProvider name="Datenschutzerklärung" rules="size:10000|ext:pdf" v-slot="{ errors, classes, validate }">
                                        <input id="dataProtection" type="file" class="form-control" :class="classes" name="dataProtection" @change="validate" ref="fileInput" >
                                        <small class="form-text text-muted ml-1" v-if="errors.length == 0">Die Datei muss ein PDF sein und darf nicht größer als 10MB sein.</small>
                                        <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                    </ValidationProvider>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Hochladen
                                    </button>

                                    <router-link class="btn btn-link" :to="{ name: 'facility.show', params: { id: facility.id } }">
                                        Zurück zu Übersicht
                                    </router-link>
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
import axios from "axios";
import FormInput from "../component/FormInput";

export default {
    components: { FormInput },
    data() {
        return {
            facility: {},
            generate: {},
            hasFile: false,
            alertShow: false,
            alertText: null
        }
    },
    methods: {
        onUpload() {
            const formData = new FormData();
            formData.append("data-protection", this.$refs.fileInput.files[0]);

            return this.$http
                .post( `/facility/${this.facility.id}/data-protection/file`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(({ data }) => {
                    this.hasFile = true;
                    this.showAlert('Datenschutzerklärung erfolgreich hochgeladen.')
                })
        },
        onGenerate() {
            return this.$http
                .post( `/facility/${this.facility.id}/data-protection`, this.generate)
                .then(({ data }) => {
                    this.hasFile = true;
                    this.showAlert('Datenschutzerklärung erfolgreich erstellt.')
                })
        },
        showAlert(text) {
            this.alertText = text;
            this.alertShow = true;

            setTimeout(() => {
                this.$refs.alert.scrollIntoView();
            }, 0)

            setTimeout(() => {
                this.alertShow = false;
                this.alertText = null;
            }, 5000); // 5s
        },
        removeFile() {
            return this.$http
                .delete( `/facility/${this.facility.id}/data-protection`)
                .then(({ data }) => {
                    this.hasFile = false;
                    this.generate = {};
                    this.showAlert('Datenschutzerklärung erfolgreich entfernt.')
                })
        }
    },
    beforeRouteEnter (to, from, next) {
        axios.get('/facility/' + to.params.id).then((response) => {
            next((vm) => {
                vm.facility = response.data;

                axios.get('/facility/' + to.params.id + '/data-protection').then(({ data }) => {
                    vm.hasFile = data.has_file;
                    vm.generate = data.generate || {};
                });
            });
        });
    }
}
</script>
