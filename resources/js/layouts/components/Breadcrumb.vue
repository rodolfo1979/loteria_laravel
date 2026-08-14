<script setup>
import {useRoute} from 'vue-router';

const route = useRoute();
const store = useAppStore();
const items = ref([]);

const updateBreadcrumbs = () => {
    items.value = route.meta.breadcrumb || [];

    if (items.value.length) {
        document.title = items.value.map((obj) => obj.name.toUpperCase()).join(' > ');
    } else {
        document.title = store.app.name;
    }
};

watch(route, updateBreadcrumbs);

onMounted(() => {
    updateBreadcrumbs();
});
</script>

<template>
    <VBreadcrumbs :items="items" v-if="route.path !== '/'">
        <template v-slot:divider>
            <v-icon icon="mdi-chevron-right"></v-icon>
        </template>
        <template v-slot:title="{ item }">
            {{ item.name.toUpperCase() }}
        </template>
    </VBreadcrumbs>
</template>


<style lang="scss" scoped>

</style>
