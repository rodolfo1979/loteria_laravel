<template>
    <v-container fluid>
        <v-row :no-gutters="$vuetify.breakpoint.xsOnly">
            <v-col cols="6" sm="4" md="3" lg="3" xl="3"  v-for="child in paginasChildrens" :key="child.cat_pagina_id">
                <v-card >
                    <v-card-title class="justify-center text-body-1 text-sm-body-1 text-md-body-1 text-lg-h6 text-xl-h6">
                        {{ child.cat_pagina_nombre }}
                    </v-card-title>
                    <v-card-text class="text-center">
                        <v-icon size="50">{{ child.cat_pagina_icono }}</v-icon>
                    </v-card-text>
                    <v-divider class="mt-1"></v-divider>
                    <v-card-actions class="justify-center py-3">
                        <VBtn outlined rounded :to="child.slug" color="info">
                            <v-icon>mdi-share-outline</v-icon>
                            Ir
                        </VBtn>
                    </v-card-actions>
                </v-card>
            </v-col>

        </v-row>

    </v-container>
</template>

<script>
import Trans from "@/services/Trans";

export default {
    name: "ParentsIndex",
    data() {
        return {
            permisos: [],
            paginasChildrens: [],
        }
    },

    methods: {

    },
    created() {
        Trans.checkPermisos(this.$route.path).then((res) => {
            this.permisos = res;
        });
        Trans.getPaginasChildrens(this.$route.path).then((res) => {
            this.paginasChildrens = res;
        });
    }
}
</script>

<style>

</style>
