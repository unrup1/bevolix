<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4 class="text-muted">{{ facility.name }}</h4>
                <h1>Gästedaten</h1>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <a class="btn btn-outline-primary" @click="toggleExportBar()">Daten exportieren</a>
                        <router-link class="ml-3" :to="{ name: 'facility.show', params: { id: facility.id } }">Zurück zu Übersicht</router-link>
                        <div v-if="openExport" class="bg-gray p-3 mt-2">
                            <h4>Export</h4>
                            <p>Exportiert alle Personen, die in diesen Zeitraum im Gebäude waren.</p>
                            <form @submit.prevent="openExportLink()">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="date">Datum <small>Pflichtfeld</small></label>
                                            <v-date-picker
                                                v-model='selectedDate'
                                                :input-props='{
                                                    class: "form-control",
                                                    placeholder: "Datum für den Export",
                                                    required: true,
                                                    id: "date"
                                                }'
                                                locale="de"
                                            />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="startTime">Anfang Uhrzeit</label>
                                            <input id="startTime" name="startTime" class="form-control" type="time" v-model="startTime">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="endTime">Ende Uhrzeit</label>
                                            <input id="endTime" name="endTime" class="form-control" type="time" v-model="endTime">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Export</button>
                                <a @click="toggleExportBar()" class="btn btn-link">Abbrechen</a>
                            </form>
                            <a :href="getExportUrl()" ref="exportLink" target="_blank" class="d-none">Ref Export</a>
                        </div>
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Telefonnummer</th>
                                    <th>Datum</th>
                                    <th>Eintritt</th>
                                    <th>Verlassen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="guest in guests">
                                    <td>{{ guest.name }}</td>
                                    <td>{{ guest.telephoneNumber }}</td>
                                    <td>{{ guest.created_at | formatDate }}</td>
                                    <td>{{ guest.startTime | formatTime }}</td>
                                    <td>{{ guest.endTime | formatTime }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'v-calendar/lib/components/date-picker.umd';
import moment from 'moment';

export default {
    components: { DatePicker },
    data () {
        return {
            facility: {},
            guests: [],
            openExport: this.$route.query.open_export || false,
            selectedDate: null,
            startTime: null,
            endTime: null
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.$http.get('/facility/' + vm.$route.params.id).then((response) => {
                vm.facility = response.data;
            })
            vm.$http.get('/facility/' + vm.$route.params.id + '/guest').then((response) => {
                vm.guests = response.data;
            })
        });
    },
    methods: {
        toggleExportBar () {
            this.openExport = !this.openExport;
        },
        openExportLink () {
            this.$refs.exportLink.click();
            this.openExport = false;
            this.selectedDate = null;
            this.startTime = null;
            this.endTime = null;
        },
        getExportUrl() {
            var appUrl = document.querySelector('meta[name="appUrl"]').content;
            var date = moment(this.selectedDate).format('YYYY-MM-DD');
            return `${appUrl}/facility/${this.facility.id}/guest/export?date=${date}&startTime=${this.startTime || ''}&endTime=${this.endTime || ''}`;
        }
    }
}
</script>
