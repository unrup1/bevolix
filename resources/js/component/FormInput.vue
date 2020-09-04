<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{ name }} <small v-if="hasRequired" class="text-muted">Pflichtfeld</small></label>
        <ValidationProvider :name="name" :rules="rules" v-slot="{ errors, classes }" :debounce="debounceTime">
            <input
                :id="id"
                type="text"
                class="form-control"
                :class="classes"
                :name="id"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :required="hasRequired"
                >
            <span class="invalid-feedback">{{ errors.join(' ') }}</span>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    props: {
        id: String,
        name: String,
        rules: String,
        value: String
    },
    data() {
        return {
            hasRequired: this.rules.indexOf('required') != -1,
            debounceTime: 400
        }
    }
}

// autocomplete="tel"
// v-model
</script>

<style scoped>

</style>
