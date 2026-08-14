<script setup>
const NavItems = defineAsyncComponent(() => import('@/layouts/components/NavItems.vue'));
const VerticalNavLayout = defineAsyncComponent(() => import('@layouts/components/VerticalNavLayout.vue'));
const Footer = defineAsyncComponent(() => import('@/layouts/components/Footer.vue'));
const UsarThemeSwitcher = defineAsyncComponent(() => import('@/layouts/components/UsarThemeSwitcher.vue'));
const UsarioSesion = defineAsyncComponent(() => import('@/layouts/components/UsuarioSesion.vue'));
const Breadcrumb = defineAsyncComponent(() => import('@/layouts/components/Breadcrumb.vue'));

const logo = new URL('@images/logo.png', import.meta.url).href

const store = useAppStore();

</script>
<template>
    <VerticalNavLayout>
        <!--navbar -->
        <template #navbar="{ toggleVerticalOverlayNavActive }">
            <div class="d-flex h-100 align-center">
                <!--Vertical nav toggle in overlay mode -->
                <IconBtn class="ms-n3" @click="toggleVerticalOverlayNavActive(true)">
                    <VIcon icon="mdi-menu"/>
                </IconBtn>

                <VSpacer/>

                <Breadcrumb></Breadcrumb>

                <VSpacer/>

                <IconBtn>
                    <VIcon icon="mdi-bell"/>
                </IconBtn>

                <UsarThemeSwitcher class="me-1"/>

                <UsarioSesion/>
            </div>
        </template>

        <template #vertical-nav-header="{ toggleIsOverlayNavActive }">
            <RouterLink to="/" class="app-logo app-title-wrapper">

                <div class="d-flex">
                    <img :src="logo" alt="" height="50px">
                </div>

                <h1 class="app-logo-title">
                    {{ store.app.name }}
                </h1>
            </RouterLink>

            <IconBtn class="d-block d-lg-none" @click="toggleIsOverlayNavActive(false)">
                <VIcon icon="mdi-bell"/>
            </IconBtn>
        </template>

        <template #vertical-nav-content>
            <NavItems/>
        </template>

        <!-- Pages -->
        <slot/>

        <!-- Footer -->
        <template #footer>
            <Footer/>
        </template>
    </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
    border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
    border-radius: 6px;
    block-size: 1.5625rem;
    line-height: 1.3125rem;
    padding-block: 0.125rem;
    padding-inline: 0.25rem;
}

.app-logo {
    display: flex;
    align-items: center;
    column-gap: 0.75rem;

    .app-logo-title {
        font-size: 1.25rem;
        font-weight: 500;
        line-height: 1.75rem;
        text-transform: uppercase;
    }
}
</style>
