<template>
    <div>
        <div class="text-center">
            <h4 class="text-muted">Besucheranmeldung</h4>
            <h1>{{ facility.name }}</h1>
            <span v-if="facility.description" v-html='facility.description'></span>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(onSubmit)" novalidate>
                        <div class="form-group">
                            <label for="name" class="form-label">Name <small class="text-muted">Pflichtfeld</small></label>
                            <ValidationProvider name="Name" rules="required|max:255" v-slot="{ errors, classes }" :debounce="debounceTime" mode="passive">
                                <input id="name" type="text" class="form-control" name="name" :class="classes" v-model="guest.name" required autofocus autocomplete="name">
                                <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group">
                            <label for="street" class="form-label">Straße <small class="text-muted">Pflichtfeld</small></label>
                            <ValidationProvider name="Straße" rules="required|max:255" v-slot="{ errors, classes }" :debounce="debounceTime">
                                <input id="street" type="text" class="form-control" :class="classes" name="street" v-model="guest.street" required autocomplete="street-address">
                                <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="form-row">
                            <div class="col form-group">
                                <label for="postalCode" class="form-label">PLZ <small class="text-muted">Pflichtfeld</small></label>
                                <ValidationProvider name="PLZ" rules="required|max:255" v-slot="{ errors, classes }" :debounce="debounceTime">
                                    <input id="postalCode" type="text" class="form-control" :class="classes" name="postalCode" v-model="guest.postalCode" required autocomplete="postal-code">
                                    <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                </ValidationProvider>
                            </div>
                            <div class="col form-group">
                                <label for="location" class="form-label">Ort <small class="text-muted">Pflichtfeld</small></label>
                                <ValidationProvider name="Ort" rules="required|max:255" v-slot="{ errors, classes }" :debounce="debounceTime">
                                    <input id="location" type="text" class="form-control" :class="classes" name="location" v-model="guest.location" required autocomplete="address-level2">
                                    <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telephoneNumber" class="form-label">Telefonnummer <small class="text-muted">Pflichtfeld</small></label>
                            <ValidationProvider name="Telefonnummer" rules="required|max:255" v-slot="{ errors, classes }" :debounce="debounceTime">
                                <input id="telephoneNumber" type="text" class="form-control" :class="classes" name="telephoneNumber" v-model="guest.telephoneNumber" required autocomplete="tel">
                                <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="form-row">
                            <div class="col form-group">
                                <label for="startTime" class="form-label">Zeitpunkt des Betretens <small class="text-muted">Pflichtfeld</small></label>
                                <ValidationProvider name="Zeitpunkt des Betretens" rules="required" v-slot="{ errors, classes }" :debounce="debounceTime">
                                    <input id="startTime" type="time" class="form-control" :class="classes" name="startTime" v-model="guest.startTime" required>
                                    <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                </ValidationProvider>
                            </div>
                            <div class="col form-group">
                                <label for="endTime" class="form-label">Zeitpunkt des Verlassens <small class="text-muted">Pflichtfeld</small></label>
                                <ValidationProvider name="Zeitpunkt des Verlassens" rules="required" v-slot="{ errors, classes }" :debounce="debounceTime">
                                    <input id="endTime" type="time" class="form-control" :class="classes" name="endTime" v-model="guest.endTime" required>
                                    <span class="invalid-feedback">{{ errors.join(' ') }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-check">
                            <ValidationProvider name="Datenschutzerklärung" rules="required" v-slot="{ errors, classes }">
                                <input class="form-check-input" type="checkbox" :class="classes" id="privacy" v-model="guest.privacy" required>
                                <label class="form-check-label" for="privacy">
                                    Ich bin mit der <a :href="`/c/${facility.corona_hash}/data-protection`" target="_blank">Datenschutzerklärung</a> der Einrichtung einverstanden. Nach 30 Tagen werden die Daten gelöscht.
                                </label>
                            </ValidationProvider>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                Daten absenden
                            </button>
                        </div>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['facility'],
    data() {
        return {
            guest: {
            },
            debounceTime: 400
        }
    },
    methods: {
        onSubmit() {
            this.$emit('send-submit', this.$data.guest);
        }
    }
}
</script>
