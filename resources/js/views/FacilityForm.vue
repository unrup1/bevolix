<template>
    <div class="row">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form @submit.prevent="onSubmit">
                        <div class="form-group">
                            <label for="name" class="form-label">Name <small class="text-muted">Pflichtfeld</small></label>
                            <input id="name" type="text" class="form-control" v-bind:class="{ 'is-invalid': invalid['name'] }" name="name" v-model="formData.name" required autofocus>
                            <span v:if="invalid['name']" class="invalid-feedback" role="alert">
                        <strong>{{ errorMessage }}</strong>
                    </span>
                        </div>
                        <div class="form-group">
                            <label v-on:click="$refs.description.quill.focus()" class="form-label">Beschreibung <small class="text-muted">Optional</small></label>
                            <quill-editor
                                ref="description"
                                v-model="formData.description"
                                :options="{ placeholder: 'Optionale Beschreibung unter dem Namen' }"
                                v-bind:class="{ 'is-invalid': invalid['description'] }"
                            ></quill-editor>
                            <span v:if="invalid['description']" class="invalid-feedback" role="alert">
                        <strong>{{ errorMessage }}</strong>
                    </span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ submitLabel }}
                            </button>

                            <router-link v-if="cancelTo != null" class="btn btn-link" :to="cancelTo">
                                Abbrechen
                            </router-link>
                        </div>
                    </form>

                    <hr>
                    <h4>Live Ansicht</h4>
                    <div class="d-flex">
                        <div>
                            <toggle-button
                                v-model="liveView"
                                :labels="{checked: 'An', unchecked: 'Aus'}"
                            />
                        </div>
                        <div class="ml-3">Bei dieser Funktion wird rechts das Eingabeformular angezeigt. Alle Änderungen werden sofort sichtbar.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col" v-if="liveView">
            <h4>Live Ansicht</h4>
            <div class="border p-2">
                <corona-guest-form v-bind:facility="formData"></corona-guest-form>
            </div>
        </div>
    </div>
</template>

<script>
import CoronaGuestForm from "./Corona/CoronaGuestForm";
import { ToggleButton } from 'vue-js-toggle-button'

export default {
    components: { CoronaGuestForm, ToggleButton },
    props: {
        submitLabel: null,
        cancelTo: null,
        facility: null
    },
    data() {
        return {
            formData: {
                name: this.facility.name || null,
                description: this.facility.description || null
            },
            invalid: [],
            errorMessage: '',
            liveView: false
        }
    },
    methods: {
        onSubmit() {
            this.$emit('send-submit', this.formData);
        }
    }
}
</script>
