<script setup>
const DatosPersonales = defineAsyncComponent(() => import('@/pages/personas/components/DatosPersonales.vue'));
const DatosAcceso = defineAsyncComponent(() => import('@/pages/personas/components/DatosAcceso.vue'));

const route = useRoute()
const activeTab = ref(route.params.tab)

// tabs
const tabs = [
    {
        title: 'Cuenta',
        icon: 'mdi-account-circle-outline',
        tab: 'cuenta',
    },
    {
        title: 'Datos de Acceso',
        icon: 'mdi-lock-open',
        tab: 'datos_acceso',
    },
]
</script>

<template>
    <div>
        <VTabs v-model="activeTab" class="v-tabs-pill">
            <VTab v-for="item in tabs" :key="item.icon" :value="item.tab">
                <VIcon size="20" start :icon="item.icon"/>
                {{ item.title }}
            </VTab>
        </VTabs>

        <VWindow v-model="activeTab" class="mt-5 disable-tab-transition">
            <!-- CUENTA DE USUARIO -->
            <VWindowItem value="cuenta">
                <DatosPersonales/>
            </VWindowItem>

            <!-- DATOS DE ACCESO -->
            <VWindowItem value="datos_acceso">
                <DatosAcceso/>
            </VWindowItem>

        </VWindow>
    </div>
</template>
