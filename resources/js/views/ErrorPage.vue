<template>
  <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1>Ups, das sollte nicht passieren.</h1>
                        <h3>Wenn dieser Fehler öfter vorkommt, melden Sie sich bitte bei uns!</h3>

                        <p>Wollen Sie zurück zur ihrer Einrichtung?</p>
                        <ul>
                            <li v-for="facility in data">
                                <router-link :to="{ name: 'facility.show', params: { id: facility.id } }" class="nav-link">{{ facility.name }}</router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            data: []
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.$http.get('/facility').then((response) => {
                vm.setData(response)
            })
        });
    },
    // when route changes and this component is already rendered,
    // the logic will be slightly different.
    /*beforeRouteUpdate (to, from, next) {
        this.data = [];
        this.$http.get('/facility').then((response) => {
            this.setData(response)
        })
    },*/
    methods: {
        setData (response) {
            this.data = response.data;
        }
    }
}
</script>
