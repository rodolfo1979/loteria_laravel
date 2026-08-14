<script setup>

const FelicidadesVendedor = defineAsyncComponent(() => import('./components/FelicidadesVendedor.vue'));
const ProximosSorteos = defineAsyncComponent(() => import('@/pages/components/ProximosSorteos.vue'));

</script>

<template>
    <VRow>
        <!--PROXIMOS TORNEOS -->
        <VCol cols="12" md="6" sm="6" order="1">
            <ProximosSorteos/>
        </VCol>

        <VCol cols="12" md="6" sm="6" order="2">
            <FelicidadesVendedor/>
        </VCol>
    </VRow>
</template>
