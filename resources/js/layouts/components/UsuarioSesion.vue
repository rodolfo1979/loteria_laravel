<script setup>
const avatarDefault = new URL('@public/images/personas/fotos/default.jpg', import.meta.url).href;

import {useRouter} from 'vue-router';
import Trans from "@/utils/Trans.js";
import Ls from '@/utils/Ls.js';

const router = useRouter();

const logout = () => {
    Trans.logout().then(() => {
        router.push({name: "Login"});
    })
}

const personaObj = reactive({
    avatar: avatarDefault,
    nombre: null,
    rol: null,
});

const loadAvatar = () => {
    // EVALUATE THAT EXISTS
    personaObj.avatar = Ls.get("foto") ?? avatarDefault;
};

onMounted(() => {
    personaObj.nombres = Ls.get("nombres");
    loadAvatar();
});

</script>
<template>
    <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success" bordered>
        <VAvatar class="cursor-pointer" color="primary" variant="tonal">
            <VImg :src="personaObj.avatar"/>

            <!-- SECTION DE LA PERSONA -->
            <VMenu activator="parent" width="230" location="bottom end" offset="14px">
                <VList>
                    <!-- FOTO Y NOMBRE -->
                    <VListItem>
                        <template #prepend>
                            <VListItemAction start>
                                <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success">
                                    <VAvatar color="primary" variant="tonal">
                                        <VImg :src="personaObj.avatar"/>
                                    </VAvatar>
                                </VBadge>
                            </VListItemAction>
                        </template>

                        <VListItemTitle class="font-weight-semibold">
                            {{ personaObj.nombres }}
                        </VListItemTitle>
                        <!-- <VListItemSubtitle>Admin</VListItemSubtitle>-->
                    </VListItem>
                    <VDivider class="my-1"/>

                    <!-- MI CUENTA -->
                    <VListItem to="/mi_cuenta" class="pa-2">
                        <template #prepend>
                            <VIcon class="me-2" icon="mdi-account-circle-outline" size="25"/>
                        </template>
                        <VListItemTitle>Mi Cuenta</VListItemTitle>
                    </VListItem>

                    <VDivider class="my-1"/>

                    <!-- CERRAR SESSION -->
                    <VListItem>
                        <VListItemTitle>
                            <VBtn color="error" append-icon="mdi-location-exit" variant="tonal" block
                                  @click.stop="logout">SALIR
                            </VBtn>
                        </VListItemTitle>
                    </VListItem>
                </VList>
            </VMenu>
            <!-- !SECTION -->
        </VAvatar>
    </VBadge>
</template>
