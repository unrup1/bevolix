import Vue from 'vue'
import { ValidationProvider, ValidationObserver, extend, configure } from 'vee-validate';
// import { required, email } from 'vee-validate/dist/rules';
import * as rules from 'vee-validate/dist/rules';
import { messages } from 'vee-validate/dist/locale/de.json';

// Form Validation
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

/** Config */
configure({
    classes: {
        valid: 'is-valid',
        invalid: 'is-invalid'
    }
})

/** Validations Rules */
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});

// with typescript
for (let [rule, validation] of Object.entries(rules)) {
    extend(rule, {
        ...validation,
        message: messages[rule]
    });
}

// console.log(messages);
/*extend('email', {
    ...email,
    message: 'Bitte geben Sie ein gültige E-Mail Adresse ein.'
});

extend('required', {
    ...required,
    message: 'Bitte füllen sie dieses Feld'
});*/
