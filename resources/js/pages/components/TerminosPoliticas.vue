<script setup>

const modal = reactive({
    open: false,
    title: "TERMINOS DE SERVICIOS Y POLÍTICAS DE PRIVACIDAD",
});

const modalOpen = () => {
    modal.open = true;
}

const modalClose = () => {
    modal.open = false;
}

// Exponer el método modalOpen para que pueda ser llamado desde el padre
defineExpose({
    modalOpen
});

</script>

<template>
    <VDialog persistent  width="60%" max-width="1200"
             transition="dialog-bottom-transition"
             :fullscreen="$vuetify.display.smAndDown"
             v-model="modal.open">
        <VCard class="">

            <VCardItem class="pb-3">
                <VCardTitle class="text-primary">
                    Terminos de Servicios
                </VCardTitle>
                <VCardText>
                    SE ESTARÁN DEFINIENDO
                </VCardText>
            </VCardItem>

            <VCardItem class="pb-3">
                <VCardTitle class="text-primary">
                    Políticas de Privacidad
                </VCardTitle>
                <VCardText>
                    SE ESTARÁN DEFINIENDO
                </VCardText>
            </VCardItem>
            <VCardActions>
                <VBtn
                    text="NO"
                    color="warning"
                    variant="plain"
                    @click.stop="modalClose"/>

                <VBtn
                    color="success"
                    text="ACEPTO"
                    variant="tonal"
                    @click.stop="modalClose"/>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<style lang="scss" scoped>
</style>
