import Vue from 'vue';
import moment from 'moment';

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY')
    }
});

/*Vue.filter('formatDateTime', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY hh:mm')
    }
});*/

Vue.filter('formatTime', function(value) {
    if (value) {
        return value.slice(0, 5);
    }
});
