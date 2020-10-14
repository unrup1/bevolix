<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{ name }} <small v-if="hasRequired" class="text-muted">Pflichtfeld</small></label>
        <ValidationProvider :name="name" :rules="rules" v-slot="{ errors, classes }" :debounce="debounceTime" :vid="vid ? id : false">
            <input
                :id="id"
                :type="type ? type : 'text'"
                class="form-control"
                :class="classes"
                :name="id"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :required="hasRequired"
                :autocomplete="autocomplete ? autocomplete : false"
                :autofocus="autofocus"
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
        value: String,
        type: {
            type: String,
            default: 'text',
            required: false
        },
        autocomplete: {
            type: String,
            required: false
        },
        vid: {
            type: Boolean,
            default: false,
            required: false
        },
        autofocus: {
            type: Boolean,
            default: false,
            required: false
        }
    },
    data() {
        return {
            hasRequired: this.rules.indexOf('required') != -1,
            debounceTime: 400
        }
    }
}

// autocomplete="tel"
</script>

<style scoped>

</style>
