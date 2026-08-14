<script setup>
import {toast} from "vue3-toastify";
import Utils from "@/utils/Utils.js";

const VerticalNavGroup = defineAsyncComponent(() => import('@layouts/components/VerticalNavGroup.vue'));
const VerticalNavLink = defineAsyncComponent(() => import('@layouts/components/VerticalNavLink.vue'));

const loading = ref(false);
const showMenu = ref(false);
const menu = ref([]);

const getMenu = () => {
    loading.value = true;
    axios.get('/permisos/menu')
        .then((response) => {
            if (response.data.success) {
                let data = response.data.data;

                // Si es array entonces asignar
                if (Array.isArray(data)) {
                    menu.value = data;
                    showMenu.value = true;
                }

            } else if (response.data.success === false) {
                toast.error(response.data.message);
            }

        }).catch((error) => {
        console.log(error);
        toast.warning('No se pudo obtener los Datos');
    }).finally(() => {
        loading.value = false;
    });
};

const expandParent = (item) => {
    const urlPart = Utils.getUrlPart(this.$route.path);
    for (const it of item.children) {
        if (urlPart === Utils.getUrlPart(it.link)) {
            return true;
        }
    }
    return false;
};



// GET MENU
onMounted(() => {
    getMenu();
});
</script>

<template>
    <template v-for="(item, index) in menu" :key="index" v-if="showMenu">

        <VerticalNavGroup v-if="item.children" :item="{ title: item.title, icon: item.icon, to: item.link, href: item.link }">
            <VerticalNavLink
                v-for="(child, childIndex) in item.children"
                :key="childIndex"
                :item="{ title: child.title, icon: child.icon, to: child.link, href: child.link }"
            />
        </VerticalNavGroup>

        <VerticalNavLink v-else :item="{ title: item.title, icon: item.icon, to: item.link, href: item.link }" />

    </template>
</template>

