<script setup>
const ThemeSwitcher = defineAsyncComponent(() => import('@layouts/components/ThemeSwitcher.vue'));
const themes = [
    {
        name: 'light',
        icon: 'mdi-sun-wireless-outline',
    },
    {
        name: 'dark',
        icon: 'mdi-weather-night',
    },
]
</script>

<template>
    <ThemeSwitcher :themes="themes"/>
</template>
