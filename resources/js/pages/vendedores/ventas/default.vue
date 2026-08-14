<script setup>
import {toast} from "vue3-toastify";
import {useRouter} from "vue-router";
import Trans from "@/utils/Trans.js";

const router = useRouter();
const Pagination = defineAsyncComponent(() => import('@/pages/components/Pagination.vue'));

const store = useAppStore();

const refFormModal = ref(null);
const loading = ref(false);
const pagination = ref({});
const ventasArr = ref([]);
const page = ref(1);
const juegosSorteosArr = ref([]);

const filters = reactive({
    search: null,
    juego_id: null,
    rowsPage: store.arrayRowsPages[0],
});

const getVentas = () => {
    loading.value = true;
    let params = {filters: filters, page: page.value};
    axios.get('/ventas', {params})
        .then((response) => {
            let data = response.data.data;
            ventasArr.value = data.result.data;
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
    await axios.get("/ventas/filters")
        .then((response) => {

            juegosSorteosArr.value = response.data.data.juegosSorteos;
            // NOW RENDER
            getVentas();

        }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo obtener los Datos');
        }).finally(() => {
            loading.value = false;
        });
};

const searchVentas = async () => {
    page.value = 1;
    getVentas();
};

const vender = () => {
    router.push({name: 'VendedoresVender'});
}

const ventaPrint = (ventaId) => {
    let params = {venta_id: ventaId};
    Trans.print("/ventas/print", params);
}

const ventaView = (ventaId) => {
    let params = {venta_id: ventaId};
    Trans.print("/ventas/print", params);
}

const changePage = (data) => {
    page.value = data.page;
    filters.rowsPage = data.rowsPage;
    getVentas();
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
                          @click.stop="vender()">
                        Nuevo
                    </VBtn>
                </VCol>

                <VCol cols="6" sm="6" md="3" lg="3" xl="3">
                    <VAutocomplete
                        v-model="filters.juego_id"
                        label="Loteria/Juego"
                        placeholder="Seleccione"
                        prepend-inner-icon="mdi-format-list-checks"
                        :items="juegosSorteosArr"
                        item-title="loteria_juego"
                        item-value="juego_id"
                        @update:search="searchVentas()"
                    />
                </VCol>

                <VCol cols="12" sm="12" md="6" lg="6" xl="6">
                    <VTextField
                        v-model="filters.search"
                        label="Buscar por Loteria, Juego, Cliente, Numero, Agencia y pulsa ENTER"
                        clearable
                        @keyup.enter="searchVentas()"
                        @click:clear="searchVentas()">
                        <template v-slot:append-inner>
                            <VBtn color="info" size="small" variant="tonal"
                                  @click.stop="searchVentas()">
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
                    <th>Fecha</th>
                    <th>Agencia</th>
                    <th>Juego</th>
                    <th>Ticket</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th class="text-right">Total {{ store.app.moneda }}</th>
                    <th>Activo</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="ventasArr.length">
                    <tr v-for="ve in ventasArr" :key="ve.venta_id">
                        <td>
                            {{ $filters.fechaHoraESP(ve.fecha_crea) }}
                        </td>
                        <td>
                            {{ ve.agencia}}
                        </td>
                        <td>
                            {{ ve.loteria }} / {{ ve.juego }}
                        </td>
                        <td>
                            {{ ve.venta_numero }}
                        </td>
                        <td>
                            {{ ve.cliente }}
                        </td>
                        <td>
                            {{ $filters.nullVacio(ve.vendedor) }}
                        </td>
                        <td class="text-right">
                            {{ $filters.moneda(ve.total) }}
                        </td>
                        <td>
                            <VChip :color="(ve.activo) ? 'success' : 'error'" variant="tonal" label size="large"
                                   density="compact">
                                {{ $filters.trueSi(ve.activo) }}
                            </VChip>
                        </td>
                        <td>
                            <div class="d-flex justify-space-between">
                                <VBtn color="info" size="small" variant="outlined" class="me-1"
                                      @click.stop="ventaView(ve.venta_id)">
                                    <VIcon>mdi-eye</VIcon>
                                </VBtn>
                                <VBtn color="info" size="small" variant="outlined"
                                      @click.stop="ventaPrint(ve.venta_id)">
                                    <VIcon>mdi-printer-pos-outline</VIcon>
                                </VBtn>
                            </div>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td class="text-center text--secondary" colspan="9">{{ store.mensajes.sinRegistros }}</td>
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

    <FormModal ref="refFormModal" @getVentas="getVentas"></FormModal>
</template>
