<script setup>
import {toast} from "vue3-toastify";
const CountDownTimer = defineAsyncComponent(() => import('@/pages/components/CountDownTimer.vue'));
const sorteosArr = ref([]);

const getData = async () => {
    await axios.get("/reportes/juegos/proximos_sorteos")
        .then((response) => {
            sorteosArr.value = response.data.data;
        }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo obtener los Datos');
        }).finally(() => {
            // DO SOME
        });
};

onMounted(() => {
    getData();
});

</script>

<template>
    <VCard>
        <VCardTitle class="d-flex">
            Sorteos
            <VSpacer/>
            <CountDownTimer :event-hours="sorteosArr.horas" />
        </VCardTitle>

        <VCardText>
            <VList class="card-list">
                <VListItem v-for="(so, index) in sorteosArr.sorteos" :key="index">
                    <template #prepend>
                        <VAvatar
                            rounded
                            variant="tonal"
                            color="success"
                            :image="so.juego_logo"
                            size="50"
                        />
                    </template>

                    <VListItemSubtitle>
                        {{ so.loteria }}
                    </VListItemSubtitle>
                    <VListItemTitle>
                        {{ so.juego }}
                    </VListItemTitle>

                    <template #append>
                        <VListItemAction>
                            <VIcon class="me-1">mdi-clock-time-ten-outline</VIcon>
                            <span class=""> {{ so.horaFmt }}</span>
                        </VListItemAction>
                    </template>
                </VListItem>
            </VList>
        </VCardText>
    </VCard>
</template>

<style lang="scss" scoped>
.card-list {
    --v-card-list-gap: 1.5rem;
}
</style>
