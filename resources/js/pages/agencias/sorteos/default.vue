<script setup>
import {toast} from "vue3-toastify";

const FormModal = defineAsyncComponent(() => import('@/pages/vendedores/ventas/components/FormModal.vue'));
const Pagination = defineAsyncComponent(() => import('@/pages/components/Pagination.vue'));

const store = useAppStore();

const refFormModal = ref(null);
const loading = ref(false);
const pagination = ref({});
const sorteosArr = ref([]);
const page = ref(1);
const loteriasArr = ref([]);

const filters = reactive({
    search: null,
    juego_id: 0,
    rowsPage: store.arrayRowsPages[0],
});

const getSorteos = () => {
    loading.value = true;
    let params = {filters: filters, page: page.value};
    axios.get('/sorteos', {params})
        .then((response) => {
            let data = response.data.data;
            sorteosArr.value = data.result.data;
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
    await axios.get("/sorteos/filters")
        .then((response) => {

            loteriasArr.value = response.data.data.loterias;
            // NOW RENDER
            getSorteos();

        }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo obtener los Datos');
        }).finally(() => {
            loading.value = false;
        });
};

const searchJuegos = async () => {
    page.value = 1;
    getSorteos();
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
    getSorteos();
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
                        v-model="filters.juego_id"
                        label="Loteria/Juego"
                        placeholder="Seleccione"
                        prepend-inner-icon="mdi-format-list-checks"
                        :items="loteriasArr"
                        item-title="nombre"
                        item-value="juego_id"
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
                    <th>Dias</th>
                    <th>Horas</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="sorteosArr.length">
                    <tr v-for="so in sorteosArr" :key="so.sorteo_id">
                        <td>
                            {{ so.loteria }}
                        </td>
                        <td>
                            {{ so.nombre }}
                        </td>
                        <td>
                            {{ so.dias }}
                        </td>
                        <td>
                            {{ so.horas }}
                        </td>
                        <td>
                            <VChip :color="(so.activo) ? 'success' : 'error'" variant="tonal" label size="large"
                                   density="compact">
                                {{ $filters.trueSi(so.activo) }}
                            </VChip>
                        </td>
                        <td>
                            <VBtn color="info" prepend-icon="mdi-pencil-outline" size="small" variant="outlined"
                                  @click.stop="renderFormModal(so.sorteo_id)">
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

    <FormModal ref="refFormModal" @getSorteos="getSorteos"></FormModal>
</template>
