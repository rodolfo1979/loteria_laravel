<script setup>
import {toast} from "vue3-toastify";

const FormModal = defineAsyncComponent(() => import('@/pages/configuracion/loterias/components/FormModal.vue'));
const Pagination = defineAsyncComponent(() => import('@/pages/components/Pagination.vue'));

const store = useAppStore();

const refFormModal = ref(null);
const loading = ref(false);
const pagination = ref({});
const loteriasArr = ref([]);
const page = ref(1);

const filters = reactive({
    search: null,
    rowsPage: store.arrayRowsPages[0],
});

const getLoterias = () => {
    loading.value = true;
    let params = {filters: filters, page: page.value};
    axios.get('/loterias', {params})
        .then((response) => {
            let data = response.data.data;
            loteriasArr.value = data.result.data;
            pagination.value = data.pagination;

        }).catch((error) => {
        console.log(error);
        toast.warning('No se pudo obtener los Datos');
    }).finally(() => {
        loading.value = false;
    });
};

const renderFormModal = (loteriaId) => {
    if (refFormModal.value) {
        refFormModal.value.modalOpen(loteriaId);
    } else {
        toast.warning('No se pudo cargar el Formulario');
    }
}

const changePage = (data) => {
    page.value = data.page;
    filters.rowsPage = data.rowsPage;
    getLoterias();
}

onMounted(() => {
    getLoterias();
});

</script>

<template>
    <VCard :loading="loading" density="compact">
        <VCardTitle class="pa-4">
            <VRow>
                <VCol cols="12" sm="6" md="6" lg="6" xl="6">
                    <VBtn prepend-icon="mdi-plus" color="success" variant="outlined"
                          @click.stop="renderFormModal(null)">
                        Nuevo
                    </VBtn>
                </VCol>

                <VCol cols="12" sm="6" md="6" lg="6" xl="6">
                    <VTextField
                        v-model="filters.search"
                        label="Buscar por Lotería y pulsa ENTER"
                        clearable
                        @keyup.enter="getLoterias()"
                        @click:clear="getLoterias()">
                        <template v-slot:append-inner>
                            <VBtn color="info" size="small" variant="tonal"
                                  @click.stop="getLoterias()">
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
                    <th>Descripción</th>
                    <th>Logo</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="loteriasArr.length">
                    <tr v-for="lo in loteriasArr" :key="lo.loteria_id">
                        <td>
                            {{ lo.nombre }}
                        </td>
                        <td>
                            {{ lo.descripcion }}
                        </td>
                        <td>
                            <VImg :src="lo.logo" :width="50" aspect-ratio="1"></VImg>
                        </td>
                        <td>
                            <VChip :color="(lo.activo) ? 'success' : 'error'" variant="tonal" label size="large"
                                   density="compact">
                                {{ $filters.trueSi(lo.activo) }}
                            </VChip>
                        </td>
                        <td>
                            <VBtn color="info" prepend-icon="mdi-pencil-outline" size="small" variant="outlined"
                                  @click.stop="renderFormModal(lo.loteria_id)">
                            </VBtn>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td class="text-center text--secondary" colspan="5">{{ store.mensajes.sinRegistros }}</td>
                    </tr>
                </template>
                </tbody>
            </VTable>
        </VCardText>
        <VDivider></VDivider>
        <VCardActions>
            <Pagination :pagination="pagination" :arrayRowsPages="store.arrayRowsPages" @changePage="changePage"
                        v-if="pagination.total"></Pagination>
        </VCardActions>

    </VCard>

    <FormModal ref="refFormModal" @getLoterias="getLoterias"></FormModal>
</template>
