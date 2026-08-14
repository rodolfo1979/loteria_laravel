<script setup>
const Hoy = defineAsyncComponent(() => import('@/pages/ver_resultados/components/Hoy.vue'));
const Historico = defineAsyncComponent(() => import('@/pages/ver_resultados/components/Historico.vue'));

const tab = ref(null)

const tabItems = [
    'Ganadores hoy',
    'Historial',
]

</script>

<template>
    <VRow>
        <VCol cols="12">
            <VCard>
                <VTabs v-model=" tab">
                    <VTab v-for="item in tabItems" :key="item" :value="item">
                        {{ item }}
                    </VTab>
                </VTabs>

                <VDivider/>

                <!-- TABS CONTENIDO -->
                <VWindow v-model="tab">
                    <VWindowItem value="Ganadores hoy">
                        <Hoy></Hoy>
                    </VWindowItem>

                    <VWindowItem value="Historial">
                        <Historico></Historico>
                    </VWindowItem>
                </VWindow>
            </VCard>
        </VCol>

    </VRow>
</template>
