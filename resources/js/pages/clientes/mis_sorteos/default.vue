<script setup>
import {toast} from "vue3-toastify";

const FormModal = defineAsyncComponent(() => import('@/pages/configuracion/juegos/components/FormModal.vue'));
const Pagination = defineAsyncComponent(() => import('@/pages/components/Pagination.vue'));

const store = useAppStore();

const refFormModal = ref(null);
const loading = ref(false);
const pagination = ref({});
const juegosArr = ref([]);
const page = ref(1);
const loteriasArr = ref([]);

const filters = reactive({
    search: null,
    loteria_id: 0,
    rowsPage: store.arrayRowsPages[0],
});

const getJuegos = () => {
    loading.value = true;
    let params = {filters: filters, page: page.value};
    axios.get('/juegos', {params})
        .then((response) => {
            let data = response.data.data;
            juegosArr.value = data.result.data;
            pagination.value = data.pagination;

        }).catch((error) => {
        console.log(error);
        toast.warning('No se pudo obtener los Datos');
    }).finally(() => {
        loading.value = false;
    });
};

// GET FILTER
const getFilters = async () => {
    loading.value = true;
    await axios.get("/juegos/filters")
        .then((response) => {

            loteriasArr.value = response.data.data.loterias;
            // NOW RENDER
            getJuegos();

        }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo obtener los Datos');
        }).finally(() => {
            loading.value = false;
        });
};

const searchJuegos = async () => {
    page.value = 1;
    getJuegos();
};

const renderFormModal = (juegoId) => {
    if (refFormModal.value) {
        refFormModal.value.modalOpen(juegoId);
    } else {
        toast.warning('No se pudo cargar el Formulario');
    }
}

const changePage = (data) => {
    page.value = data.page;
    filters.rowsPage = data.rowsPage;
    getJuegos();
}

onMounted(() => {
    getFilters();
});

</script>

<template>
    <VCard :loading="loading" density="compact">
        <VCardTitle class="pa-4">
            <VRow>
                <VCol cols="6" sm="6" md="3" lg="3" xl="3">
                    <VBtn prepend-icon="mdi-plus" color="success" variant="outlined"
                          @click.stop="renderFormModal(null)">
                        Nuevo
                    </VBtn>
                </VCol>

                <VCol cols="6" sm="6" md="3" lg="3" xl="3">
                    <VAutocomplete
                        v-model="filters.loteria_id"
                        label="Loteria"
                        placeholder="Seleccione"
                        prepend-inner-icon="mdi-format-list-checks"
                        :items="loteriasArr"
                        item-title="nombre"
                        item-value="loteria_id"
                        @update:search="searchJuegos()"
                    />
                </VCol>

                <VCol cols="12" sm="12" md="6" lg="6" xl="6">
                    <VTextField
                        v-model="filters.search"
                        label="Buscar por Loteria, Juego y pulsa ENTER"
                        clearable
                        @keyup.enter="searchJuegos()"
                        @click:clear="searchJuegos()">
                        <template v-slot:append-inner>
                            <VBtn color="info" size="small" variant="tonal"
                                  @click.stop="searchJuegos()">
                                <v-icon>mdi-magnify</v-icon>
                            </VBtn>
                        </template>
                    </VTextField>
                </VCol>

            </VRow>
        </VCardTitle>
        <VDivider></VDivider>
        <VCardText class="pa-2">

            <VTable fixed-header hover density="compact">
                <thead>
                <tr>
                    <th>Lotería</th>
                    <th>Juego</th>
                    <th>Descripción</th>
                    <th>Logo</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="juegosArr.length">
                    <tr v-for="ju in juegosArr" :key="ju.juego_id">
                        <td>
                            {{ ju.loteria }}
                        </td>
                        <td>
                            {{ ju.nombre }}
                        </td>
                        <td>
                            {{ ju.descripcion }}
                        </td>
                        <td>
                            <VImg :src="ju.logo" :width="50" aspect-ratio="1"></VImg>
                        </td>
                        <td>
                            <VChip :color="(ju.activo) ? 'success' : 'error'" variant="tonal" label size="large"
                                   density="compact">
                                {{ $filters.trueSi(ju.activo) }}
                            </VChip>
                        </td>
                        <td>
                            <VBtn color="info" prepend-icon="mdi-pencil-outline" size="small" variant="outlined"
                                  @click.stop="renderFormModal(ju.juego_id)">
                            </VBtn>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td class="text-center text--secondary" colspan="6">{{ store.mensajes.sinRegistros }}</td>
                    </tr>
                </template>
                </tbody>
            </VTable>

        </VCardText>
        <VDivider></VDivider>
        <VCardActions dense>
            <Pagination :pagination="pagination" :arrayRowsPages="store.arrayRowsPages" @changePage="changePage"
                        v-if="pagination.total"></Pagination>
        </VCardActions>

    </VCard>

    <FormModal ref="refFormModal" @getJuegos="getJuegos"></FormModal>
</template>
