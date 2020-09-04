<template>
    <div>
        <div class="text-center">
            <h4 class="text-muted">Besucheranmeldung</h4>
            <h1>{{ facility.name }}</h1>
            <p v-if="facility.description">{{ facility.description }}</p>
        </div>
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <ion-icon name="checkmark-circle-outline" class="big text-success"></ion-icon>
                <h4>Daten erfolgreich abgesendet!</h4>
                <button type="button" class="btn btn-primary mt-3" @click="close">Fenster schließen</button>
                <div>
                    <button type="button" class="btn btn-link mt-3" @click="reload">Weitere Person hinzufügen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['facility'],
    methods: {
        reload() {
            window.location.reload();
        },
        close() {
            window.close();
        }
    }
}
</script>
