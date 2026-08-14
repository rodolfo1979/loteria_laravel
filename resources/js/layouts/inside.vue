<script setup>
const Layaout = defineAsyncComponent(() => import('@/layouts/components/Layaout.vue'));

</script>

<template>
  <Layaout>
    <RouterView />
  </Layaout>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
