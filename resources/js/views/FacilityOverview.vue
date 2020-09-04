<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Einrichtungen</h1>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="facility in data">
                                    <td>{{ facility.name }}</td>
                                    <td>
                                        <router-link :to="{ name: 'facility.show', params: { id: facility.id } }">Öffnen</router-link> |
                                        <a target="_blank" :href="`/c/${facility.corona_hash}`">Formular öffnen</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <router-link :to="{ name: 'facility.new' }">Neue Einrichtung hinzufügen</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            data: []
        }
    },
    beforeRouteEnter (to, from, next) {
        axios.get('/facility').then((response) => {
            next((vm) => {
                vm.data = response.data;
            });
        });
    }
}
</script>
