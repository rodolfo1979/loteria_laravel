<script setup>
import {toast} from "vue3-toastify";
import lottoBanner1 from '@images/banner/lottoBanner1Opacity.png'
import {useRouter} from "vue-router";
import Trans from "@/utils/Trans.js";

const router = useRouter();
const store = useAppStore();

const ClienteFormBasicoModal = defineAsyncComponent(() => import('@/pages/personas/components/FormBasicoModal.vue'));

const refClienteFormBasicoModal = ref(null);
const loading = ref(false);
const personasArr = ref([]);
const juegosArr = ref([]);
const juegosHorasArr = ref([]);
const juegosFormasGanarArr = ref([]);

const ventaDetallesArr = ref([]);
const formValidVentaDetalle = ref(true);

const ventaModel = reactive({
    venta_id: 0,
    agencia_id: 1,
    numero: 1,
    cliente_id: null,
    juego_id: null,
    fecha_sorteo: store.fechaHoy,
    mecanismo_juego_id: null,
    mecanismo_juego: null,
    total: 0,
    detalles: [],
});

const ventaDetalleModel = reactive({
    venta_detalle_id: 0,
    juego_forma_ganar_id: null,
    modalidad: null,
    numero: null,
    monto: null,
    hora: null,
    eliminado: false,
    activo: true,
});

const ventaForm = reactive({
    texto: {
        rules: [(v) => !!v || store.validaciones.campoRequerido]
    },
    numero99: {
        rules: [(v) => !!v && v <= 99 || store.validaciones.numero99]
    },
    numero9: {
        rules: [(v) => !!v && v <= 9 || store.validaciones.numero9]
    },
    numeroMayorCero: {
        rules: [(v) => Number(v) > 0 || store.validaciones.numeroMayorCero]
    }
});

// GET CREATE
const getCreate = async () => {
    loading.value = true;
    await axios.get("/ventas/create")
        .then((response) => {
            let data = response.data.data;
            juegosArr.value = data.juegosSorteos;
            personasArr.value = data.personas;
        }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo obtener los Datos');
        }).finally(() => {
            loading.value = false;
        });
};

// EVENTOS MASTER

const changeJuego = () => {
    // RESET
    juegosHorasArr.value = [];
    juegosFormasGanarArr.value = [];
    ventaDetalleModelReset();
    // FIND
    juegosArr.value.forEach((so) => {
        if (Number(ventaModel.juego_id) === Number(so.juego_id)) {
            juegosHorasArr.value = so.horas;
            juegosFormasGanarArr.value = so.formas_ganar;
            ventaModel.mecanismo_juego_id = so.mecanismo_juego_id;
            ventaModel.mecanismo_juego = so.mecanismo_juego;
        }
    });
}

const changeFormaGanar = () => {
    // RESET
    ventaDetalleModel.modalidad = null;
    // FIND
    juegosFormasGanarArr.value.forEach((jfg) => {
        if (Number(ventaDetalleModel.juego_forma_ganar_id) === Number(jfg.juego_forma_ganar_id)) {
            ventaDetalleModel.modalidad = jfg.modalidad;
        }
    });
}

const ventasListado = () => {
    router.push({name: 'VendedoresVentas'});
}

const ventaSave = () => {
    if (formValidVentaDetalle.value) {
        loading.value = true;

        // ADD DETALLES
        ventaModel.detalles = ventaDetallesArr.value;

        axios.post('/ventas/save', ventaModel)
            .then((response) => {
                if (response.data.success) {
                    toast.success(response.data.message);
                    let ventaId = response.data.data;
                    // EMIT CLOSE
                    setTimeout(() => {
                        // RESET VALUES
                        ventaPrint(ventaId);
                        ventaDetalleModelReset();
                        ventaDetalleDeleteAll();
                    }, 500);
                } else {
                    toast.warning(response.data.message);
                }
            }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo Registrar');
        }).finally(() => {
            loading.value = false;
        });
    } else {
        toast.warning('Complete la información requerida');
    }
}

// EVENTOS DE LA VENTA DETALLES
const ventaDetalleAdd = () => {
    // EVALUAR SI ES MODO TIEMPOS
    ventaDetallesArr.value.push({...ventaDetalleModel});
}

const ventaDetalleModelReset = () => {
    ventaDetalleModel.hora = null;
    ventaDetalleModel.juego_forma_ganar_id = null;
    ventaDetalleModel.modalidad = null;
    ventaDetalleModel.numero = null;
    ventaDetalleModel.monto = null;
    formValidVentaDetalle.value = false;
}

const ventaDetalleDelete = (index) => {
    ventaDetallesArr.value.splice(index, 1);
}

const ventaDetalleDeleteAll = () => {
    ventaDetallesArr.value = [];
}

const sumaTotal = computed(() => {
    ventaModel.total = ventaDetallesArr.value.reduce((total, item) => Number(total) + Number(item.monto), 0);
    return ventaModel.total;
});

// EVENTOS TERCEROS
const renderClienteFormBasicoModal = () => {
    if (refClienteFormBasicoModal.value) {
        refClienteFormBasicoModal.value.modalOpen();
    } else {
        toast.warning('No se pudo cargar el Formulario');
    }
}

const ventaPrint = (ventaId) => {
    let params = {venta_id: ventaId};
    Trans.print("/ventas/print", params);
}

onMounted(() => {
    getCreate();
});

</script>

<template>
    <VCard rounded="lg" :loading="loading" class="pa-0 ma-0" :image="lottoBanner1">
        <VCardText :class="$vuetify.display.mdAndUp ? '' : 'px-0'">
            <!-- GENERAL-->
            <VRow>
                <!-- LEFT -->
                <VCol cols="12" sm="5" md="5" lg="5" xl="5">
                    <VCardTitle class="pt-0">
                        Nueva oportunidad para un Ganador
                    </VCardTitle>
                    <VCard class="pa-3 pt-4" color="primary" variant="outlined">
                        <VRow>
                            <VCol cols="12">
                                <VTextField
                                    v-model="ventaModel.fecha_sorteo"
                                    type="date"
                                    label="Fecha del Sorteo"
                                    :rules="ventaForm.texto.rules"
                                    :readonly="Boolean(ventaDetallesArr.length)"
                                />
                            </VCol>
                            <VCol cols="12">
                                <VAutocomplete
                                    v-model="ventaModel.cliente_id"
                                    label="Cliente"
                                    placeholder="Seleccione"
                                    prepend-inner-icon="mdi-format-list-checks"
                                    :items="personasArr"
                                    item-title="nombres"
                                    item-value="persona_id"
                                    :rules="ventaForm.texto.rules">
                                    <template v-slot:append>
                                        <VBtn color="info" variant="outlined" size="small" title="Nuevo Cliente"
                                              @click.stop="renderClienteFormBasicoModal()">
                                            <VIcon size="20">mdi-crown</VIcon>
                                        </VBtn>
                                    </template>
                                </VAutocomplete>
                            </VCol>
                            <VCol cols="12" class="">
                                <VAutocomplete
                                    v-model="ventaModel.juego_id"
                                    label="Juego"
                                    placeholder="Seleccione"
                                    prepend-inner-icon="mdi-format-list-checks"
                                    :items="juegosArr"
                                    item-title="loteria_juego"
                                    item-value="juego_id"
                                    :rules="ventaForm.texto.rules"
                                    @update:modelValue="changeJuego"
                                    :readonly="Boolean(ventaDetallesArr.length)"
                                />
                            </VCol>
                            <VCol cols="12" class="d-flex justify-center pt-0"
                                  v-if="Number(ventaModel.mecanismo_juego_id)">
                                <VChip class="text-center ma-0 text-white" variant="flat" color="ligth" rounded>
                                    <VIcon color="success" class="me-1">mdi-bullseye-arrow mdi</VIcon>
                                    {{ ventaModel.mecanismo_juego }}
                                </VChip>
                            </VCol>

                        </VRow>
                    </VCard>

                    <VCard class="pa-3 mt-3">
                        <VForm ref="formVentaDetalle" class="mt-3" v-model="formValidVentaDetalle">
                            <VRow>
                                <VCol cols="12" class="pt-1">
                                    <VSelect
                                        v-model="ventaDetalleModel.hora"
                                        label="Hora del Sorteo"
                                        placeholder="Seleccione"
                                        prepend-inner-icon="mdi-format-list-checks"
                                        :items="juegosHorasArr"
                                        item-title="hora_fmt"
                                        item-value="hora"
                                        :rules="ventaForm.texto.rules"
                                    />
                                </VCol>
                                <VCol cols="12">
                                    <VSelect
                                        v-model="ventaDetalleModel.juego_forma_ganar_id"
                                        label="Modalidad/Jugada"
                                        placeholder="Seleccione"
                                        prepend-inner-icon="mdi-format-list-checks"
                                        :items="juegosFormasGanarArr"
                                        item-title="modalidad"
                                        item-value="juego_forma_ganar_id"
                                        @update:modelValue="changeFormaGanar"
                                        :rules="ventaForm.texto.rules"
                                    />
                                </VCol>
                                <!-- SI ES TIEMPOS-->
                                <template v-if="Number(ventaModel.mecanismo_juego_id) === 1">
                                    <VCol cols="12">
                                        <VTextField
                                            v-model="ventaDetalleModel.numero"
                                            type="number"
                                            label="Número"
                                            placeholder="Escriba"
                                            prepend-inner-icon="mdi-numeric"
                                            :rules="ventaForm.numero99.rules"
                                            oninput="if(Number(this.value) < 0) this.value = 0;"
                                        />
                                    </VCol>
                                </template>
                                <template v-if="Number(ventaModel.mecanismo_juego_id) === 2">
                                    <VCol cols="12" class="py-0">
                                        <div class="pr-5">Números</div>
                                        <VOtpInput
                                            height="50"
                                            length="3"
                                            class="ma-0 pa-0"
                                            type="number"
                                            v-model="ventaDetalleModel.numero"
                                            oninput="if(Number(this.value) < 0) this.value = 0;"
                                            required
                                            :rules="ventaForm.numero9.rules">
                                        </VOtpInput>
                                    </VCol>
                                </template>

                                <VCol cols="12">
                                    <VTextField
                                        v-model="ventaDetalleModel.monto"
                                        type="number"
                                        label="Inversión"
                                        placeholder="Escriba"
                                        prepend-inner-icon="mdi-currency-usd"
                                        :rules="ventaForm.numeroMayorCero.rules"
                                        oninput="if(Number(this.value) < 0) this.value = 0;"
                                    />
                                </VCol>
                                <VCol cols="12" class="d-flex justify-space-between">
                                    <VBtn color="warning" variant="text" rounded class="text-right"
                                          @click.stop="ventaDetalleModelReset()">
                                        Reset
                                    </VBtn>
                                    <VBtn color="info" rounded variant="outlined" class="text-right"
                                          :disabled="!formValidVentaDetalle"
                                          @click.stop="ventaDetalleAdd()" append-icon="mdi-send-circle-outline">
                                        Agregar
                                    </VBtn>
                                </VCol>
                            </VRow>
                        </VForm>
                    </VCard>

                    <!-- END-->
                </VCol>

                <VDivider vertical></VDivider>

                <!-- RIGTH -->
                <VCol cols="12" sm="7" md="7" lg="7" xl="7">
                    <VRow>
                        <VCol cols="12" :class="$vuetify.display.mdAndUp ? '' : 'px-0'">
                            <VTable fixed-header fixed-footer hover density="compact"
                                    style="max-height: 60vh; height: 60vh">
                                <thead>
                                <tr>

                                    <th v-if="$vuetify.display.mdAndUp"></th>
                                    <th>Sorteo</th>
                                    <th>Modalidad</th>
                                    <th v-if="$vuetify.display.mdAndUp">Número</th>
                                    <th v-if="$vuetify.display.mdAndDown">Núm</th>
                                    <th class="text-right" v-if="$vuetify.display.mdAndUp">Inversión</th>
                                    <th class="text-right" v-if="$vuetify.display.mdAndDown">{{ store.app.moneda }}
                                        Inv
                                    </th>
                                    <th class="text-center">
                                        <VBtn color="error" :size="$vuetify.display.mdAndUp ? 'small' : 'x-small'"
                                              variant="outlined"
                                              @click.stop="ventaDetalleDeleteAll()">
                                            Borrar
                                        </VBtn>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="ventaDetallesArr.length">
                                    <tr v-for="(vd, idx) in ventaDetallesArr" :key="idx">
                                        <td v-if="$vuetify.display.mdAndUp">
                                            N° {{ idx + 1 }}
                                        </td>
                                        <td>
                                            {{ $filters.horaESP(vd.hora) }}
                                        </td>
                                        <td>
                                            {{ vd.modalidad }}
                                        </td>
                                        <td>
                                            {{ vd.numero }}
                                        </td>
                                        <td class="text-right">
                                            {{ $filters.moneda(vd.monto) }}
                                        </td>
                                        <td class="text-center">
                                            <VBtn color="error" size="x-small" title="Borrar"
                                                  variant="outlined" @click.stop="ventaDetalleDelete(idx)">
                                                <VIcon>mdi-trash-can-outline</VIcon>
                                            </VBtn>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="text-center text--secondary" colspan="6">
                                            {{ store.mensajes.sinApuestas }}
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td v-if="$vuetify.display.mdAndDown" class="text-left bg-info">
                                        N° {{ ventaDetallesArr.length }}
                                    </td>
                                    <th :colspan="$vuetify.display.mdAndUp ? 4 : 2" class="text-right bg-info">
                                        <span class="text-white">Inversión Total: {{ store.app.moneda }}</span>
                                    </th>
                                    <th colspan="2" class="text-left bg-success">
                                        <span class="text-h5 text-white">
                                            {{ $filters.moneda(sumaTotal) }}
                                        </span>
                                    </th>
                                </tr>
                                </tfoot>
                            </VTable>
                        </VCol>
                        <VCol cols="12">
                            <VRow>
                                <VCol cols="12">
                                    <div class="">
                                    </div>
                                </VCol>
                                <VDivider/>
                                <VCol cols="12" class="d-flex justify-space-between">

                                    <VBtn color="secondary" variant="text" rounded class="text-right"
                                          @click.stop="ventasListado()">
                                        <VIcon>mdi-arrow-left</VIcon>
                                        Listado
                                    </VBtn>

                                    <VBtn color="success" variant="flat" rounded
                                          :disabled="loading || !ventaDetallesArr.length"
                                          @click.stop="ventaSave()">
                                        <VIcon>mdi-cash-register</VIcon>
                                        Finalizar y Cobrar
                                    </VBtn>
                                </VCol>
                            </VRow>

                        </VCol>
                    </VRow>

                </VCol>
            </VRow>

        </VCardText>

    </VCard>

    <ClienteFormBasicoModal ref="refClienteFormBasicoModal" @getPersonas="getCreate"></ClienteFormBasicoModal>

</template>
