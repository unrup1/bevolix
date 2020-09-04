<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8" v-if="facility != null">
                <h5 class="text-muted">Einrichtung</h5>
                <h2>{{ facility.name }}</h2>
                <facility-form
                    submit-label="Speichern"
                    v-bind:cancel-to="{ name: 'facility.show', params: { id: facility.id } }"
                    v-bind:facility="facility"
                    @send-submit="onSubmit"
                ></facility-form>
            </div>
        </div>
    </div>
</template>

<script>
import FacilityForm from "./FacilityForm";
import axios from "axios";

export default {
    components: { FacilityForm },
    data () {
        return {
            facility: null
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.$http.get('/facility/' + vm.$route.params.id).then((response) => {
                vm.facility = response.data;
            })
        });
    },
    methods: {
        onSubmit(formData) {
            return axios
                .post('/facility/' + this.facility.id, formData)
                .then(({ data }) => {
                    this.$router.push({ name: 'facility.show', params: { id: this.facility.id }, query: { saved: true} })
                })
        }
    }
}
</script>
