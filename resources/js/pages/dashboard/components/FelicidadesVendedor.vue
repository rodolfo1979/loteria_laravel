<script setup>

</script>

<template>
    <VCard class="text-center text-sm-start">
        <VRow>
            <VCol cols="12" sm="8" order="2" order-sm="1">
                <VCardItem class="pb-3">
                    <VCardTitle class="text-primary">
                        Felicidades, haz superado la Meta de Ventas! 🎉
                    </VCardTitle>
                </VCardItem>

                <VCardText>
                    Meta del mes: $,1200
                    <br>
                    Tus ventas: $1,500
                    <br>
                    <VBtn color="info" variant="outlined" class="mt-6" size="small" rounded>
                        Ver mis Ventas
                    </VBtn>
                </VCardText>
            </VCol>
        </VRow>
    </VCard>
</template>

<style lang="scss" scoped>
.john-illustration {
    inset-block-end: -0.125rem;
    inset-inline-end: 3.5rem;
}
</style>
