<template>
    <div></div>
</template>
<script>
import axios from "axios";

export default {
    beforeRouteEnter (to, from, next) {
        axios.get('/facility').then(({ data }) => {
            next((vm) => {
                vm.setData(data)
            });
        })
    },
    methods: {
        setData (data) {
            // when no one exist go to new facility
            if (data.length === 0) {
                this.$router.push({ name: 'facility.new', query: { redirect: true } })
            } else if (data.length === 1) {
                this.$router.push({ name: 'facility.show', params: { id: data[0].id } })
            } else {
                this.$router.push({ name: 'facility.overview' })
            }
        }
    }
}
</script>
