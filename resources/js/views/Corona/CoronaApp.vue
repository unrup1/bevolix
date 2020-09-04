<template>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 p-4">
                    <corona-guest-finish v-if="success" :facility="facility"></corona-guest-finish>
                    <corona-guest-form v-else :facility="facility" @send-submit="handleSubmit"></corona-guest-form>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
import CoronaGuestForm from "./CoronaGuestForm";
import CoronaGuestFinish from "./CoronaGuestFinish";

export default {
    components: { CoronaGuestForm, CoronaGuestFinish },
    data() {
        return {
            success: false,
            facility: facility
        }
    },
    methods: {
        handleSubmit(value) {
            console.log('submit', value);
            this.$http
                .put('/form/' + this.facility.corona_hash + '/guest', value)
                .then(({ data }) => {
                    this.success = true;
                })
        }
    }
}
</script>
