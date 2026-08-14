<script setup>
const misc404 = new URL('@images/pages/404.png', import.meta.url).href
</script>
<template>
    <div class="misc-wrapper">

        <div class="text-center">
            <!--Title and subtitle -->
            <h1 class="header-title font-weight-medium mb-2">
                Oops!
            </h1>
            <h2 class="text-h2 font-weight-medium mb-2 text-error">
                Página no encontrada
            </h2>
            <p class="text-body-1 mb-6">
                Por alguna razón no existe la página que estas buscando.
            </p>
        </div>

        <VBtn color="info" to="/" class="mb-6" variant="outlined" rounded outlined prepend-icon="mdi-chevron-left-circle-outline">
            Regresar
        </VBtn>

        <!-- Image -->
        <div class="misc-avatar w-100 text-center">
            <VImg
                :src="misc404"
                alt="Página no encontrada"
                :max-width="200"
                class="mx-auto"
            />
        </div>
    </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
