<script setup>
import {toast} from "vue3-toastify";

const navigationTab = ref(null)

const tabItems = [
    'Preguntas frecuentes',
    'Documentos',
    'Videos',
]

const tabContent = 'En desarrollo...'

const abrirWhatsapp = () => {
    toast.warning("En Desarrollo");
}

</script>

<template>
    <VRow>
        <VCol cols="12">
            <VCard>
                <VTabs v-model=" navigationTab">
                    <VTab v-for="item in tabItems" :key="item" :value="item">
                        {{ item }}
                    </VTab>
                </VTabs>

                <VDivider/>

                <!-- tabs content -->
                <VWindow v-model="navigationTab">
                    <VWindowItem
                        v-for="item in tabItems"
                        :key="item"
                        :value="item">
                        <VCardItem>
                            <VCardTitle>Aqui dejamos toda la información</VCardTitle>
                        </VCardItem>

                        <VCardText>
                            {{ tabContent }}
                        </VCardText>

                        <VCardText>
                            <VBtn prepend-icon="mdi-whatsapp" color="success" variant="outlined" rounded @click.stop="abrirWhatsapp">Escribenos a Whatsapp</VBtn>
                        </VCardText>
                    </VWindowItem>
                </VWindow>
            </VCard>
        </VCol>

    </VRow>
</template>
