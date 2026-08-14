<script setup>
const avatarDefault = new URL('@public/images/juegos/logos/default.png', import.meta.url).href;

import {useAppStore} from '@/plugins/store/appStore.js';
import {toast} from "vue3-toastify";

const store = useAppStore();

const modal = reactive({
    open: false,
    title: null,
});

let juegoObj = {
    juego_id: 0,
    loteria_id: null,
    nombre: null,
    descripcion: null,
    mecanismo_juego_id: null,
    logo: null,
    avatar: avatarDefault,
    activo: true,
    dias: [],
    horas: [],
    formas_ganar: [],
}

let juegoHorasObj = {
    juego_id: 0,
    juego_hora_id: 0,
    hora: null,
    activo: true,
    editando: false,
    eliminado: false,
}

let juegoFormasGanarObj = {
    juego_id: 0,
    juego_forma_ganar_id: 0,
    modalidad: null,
    calculo_jugada_id: null,
    premio_veces: null,
    orden_listado: null,
    activo: true,
    editando: false,
    eliminado: false,
}

const inputLogo = ref();
const formValid = ref(true);
const loading = ref(false);
const saved = ref(false);
const juegoModel = ref(structuredClone(juegoObj));
const juegoHoraModel = ref(structuredClone(juegoHorasObj));
const juegoFormaGanarModel = ref(structuredClone(juegoFormasGanarObj));
const loteriasArr = ref([]);
const juegoHorasArr = ref([]);
const juegoFormasGanarArr = ref([]);
const diasArr = ref([]);
const mecanismosJuegosArr = ref([]);
const calculosJugadasArr = ref([]);

const emit = defineEmits(["getJuegos"]);

const juegoForm = reactive({
    texto: {
        rules: [(v) => !!v || store.validaciones.campoRequerido],
    },
});

const changeAvatar = file => {
    const fileReader = new FileReader()
    const {files} = file.target

    if (files && files.length) {
        fileReader.readAsDataURL(files[0])

        // Guardamos el archivo seleccionado
        juegoModel.value.logo = files[0];
        fileReader.onload = () => {
            if (typeof fileReader.result === 'string')
                juegoModel.value.avatar = fileReader.result
        }
    }
}

const loadAvatar = (avatarName) => {
    juegoModel.value.avatar = avatarName;
    juegoObj.avatar = avatarName;
};

const modalOpen = async (juegoId) => {
    modal.open = true;
    modal.title = Number(juegoId) ? "Editar Juego" : "Nuevo Juego";
    juegoObj.juego_id = juegoId;
    juegoModel.value = structuredClone(juegoObj)
    loadAvatar(avatarDefault);
    saved.value = false;
    // GET EDIT DATA
    await getEdit();
}

// GET TO CREATE
const getCreate = async () => {
    loading.value = true;
    await axios.get("/juegos/create")
        .then((response) => {
            let data = response.data.data;
            loteriasArr.value = data.loterias;
            diasArr.value = data.dias;
            mecanismosJuegosArr.value = data.mecanismos_juegos;
            calculosJugadasArr.value = data.calculos_jugadas;

        }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo obtener los Datos');
        }).finally(() => {
            loading.value = false;
        });
};

// GET TO EDIT
const getEdit = async () => {
    if (juegoModel.value.juego_id) {
        loading.value = true;
        let params = {juego_id: juegoModel.value.juego_id}
        await axios.get("/juegos/edit", {params})
            .then((response) => {

                let juego = response.data.data;

                // ASSING VALUES
                juegoModel.value = juego;
                juegoHorasArr.value = juego.horas;
                juegoFormasGanarArr.value = juego.formas_ganar;

                // CALL PICTURE
                loadAvatar(juegoModel.value.logo);

            }).catch((error) => {
                console.log(error);
                toast.warning('No se pudo obtener los Datos');
            }).finally(() => {
                loading.value = false;
            });
    } else {
        // ASIGNAR ALGUNOS VALORES DEFAULT
        juegoModel.value.dias = [1,2,3,4,5,6,7];
    }
};

const juegoSave = () => {
    if (formValid.value) {
        loading.value = true;

        const formData = new FormData();

        // ADD DATA FormData
        formData.append('juego_id', juegoModel.value.juego_id ?? 0);
        formData.append('loteria_id', juegoModel.value.loteria_id);
        formData.append('nombre', juegoModel.value.nombre);
        formData.append('descripcion', juegoModel.value.descripcion);
        formData.append('logo', juegoModel.value.logo);
        formData.append('mecanismo_juego_id', juegoModel.value.mecanismo_juego_id);
        formData.append('dias', juegoModel.value.dias);

        // FOREACH AND APPEND
        juegoHorasArr.value.forEach((hora, index) => {
            formData.append(`horas[${index}]`, JSON.stringify(hora));
        });

        // FOREACH AND APPEND
        juegoFormasGanarArr.value.forEach((forma, index) => {
            formData.append(`formas_ganar[${index}]`, JSON.stringify(forma));
        });

        axios.post('/juegos/save', formData)
            .then((response) => {
                if (response.data.success) {
                    toast.success(response.data.message);
                    saved.value = true;
                    // EMIT CLOSE
                    setTimeout(() => {
                        modalClose();

                    }, 2000);
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
};

const modalClose = () => {
    modal.open = false;
    // VALIDAR SI SE GUARDO
    if (saved.value) {
        emit("getJuegos");
    }
}

const resetForm = () => {
    modal.open = false;
}
// Exponer el método modalOpen para que pueda ser llamado desde el padre
defineExpose({
    modalOpen
});

// METHODS JUEGOS HORAS
const juegoHorasSort = () => {
    juegoHorasArr.value.sort((a, b) => parseFloat(a.hora) - parseFloat(b.hora));
}

const juegoHorasAdd = () => {
    // BUSCAR EN ARRAY
    juegoHorasArr.value.forEach((jh) => {
        if (jh.hora === juegoHoraModel.value.hora && !Boolean(jh.eliminado)) {
            juegoHoraModel.value.hora = null;
            toast.warning('Ya existe la Hora registrada.');
        }
    });

    // VALIDAR
    if (juegoHoraModel.value.hora) {
        juegoHorasArr.value.push({...juegoHoraModel.value});
        // RESET
        juegoHorasReset();
        // SORT
        juegoHorasSort();
    } else {
        toast.warning('Selecciona una hora del Sorteo');
    }
}

const juegoHorasReset = () => {
    juegoHoraModel.value = structuredClone(juegoHorasObj);
}

const juegoHorasEdit = (index) => {
    juegoHorasArr.value[index].editando = true;
}

const juegoHorasSave = (index) => {
    if (juegoHorasArr.value[index].value.hora) {
        juegoHorasArr.value[index].editando = false;
        // SORT
        juegoHorasSort();
    } else {
        toast.warning('Selecciona una hora del Sorteo');
    }
}

const juegoHorasDelete = (index) => {
    juegoHorasArr.value[index].eliminado = true;
    // SORT
    juegoHorasSort();
}

// METHODS JUEGOS FORMAS DE GANAR
const juegoFormasGanarSort = () => {
    juegoFormasGanarArr.value.sort((a, b) => Number(a.orden_listado) - Number(b.orden_listado));
}

const juegoFormasGanarAdd = () => {
    // BUSCAR EN ARRAY
    juegoFormasGanarArr.value.forEach((jfg) => {
        if (jfg.modalidad === juegoFormaGanarModel.value.modalidad && !Boolean(jfg.eliminado)) {
            juegoFormaGanarModel.value.modalidad = null;
            toast.warning('Ya existe la Modalidad de ganar.');
        }
    });

    // VALIDAR
    if (juegoFormaGanarModel.value.modalidad && juegoFormaGanarModel.value.calculo_jugada_id
        && juegoFormaGanarModel.value.premio_veces && juegoFormaGanarModel.value.orden_listado) {
        juegoFormasGanarArr.value.push({...juegoFormaGanarModel.value});
        // RESET
        juegoFormasGanarReset();
        // SORT
        juegoFormasGanarSort();
    } else {
        toast.warning('Complete los campos');
    }
}


const juegoFormasGanarReset = () => {
    juegoFormaGanarModel.value = structuredClone(juegoFormasGanarObj);
}

const juegoFormasGanarEdit = (index) => {
    juegoFormasGanarArr.value[index].editando = true;
}

const juegoFormasGanarSave = (index) => {
    // VALIDAR
    if (juegoFormasGanarArr.value[index].modalidad && juegoFormasGanarArr.value[index].calculo_jugada_id
        && juegoFormasGanarArr.value[index].premio_veces && juegoFormasGanarArr.value[index].orden_listado) {
        juegoFormasGanarArr.value[index].editando = false;
        // SORT
        juegoFormasGanarSort();
    } else {
        toast.warning('Complete los campos');
    }
}

const juegoFormasGanarDelete = (index) => {
    juegoFormasGanarArr.value[index].eliminado = true;
    // SORT
    juegoFormasGanarSort();
}

onMounted(() => {
    getCreate();
});

</script>

<template>
    <VDialog persistent
             width="90%"
             max-width="1200"
             transition="dialog-bottom-transition"
             :fullscreen="$vuetify.display.smAndDown"
             v-model="modal.open">
        <VCard rounded="lg">
            <VCardTitle class="d-flex justify-space-between align-center">
                <div class=" text-medium-emphasis ps-2">
                    {{ modal.title }}
                </div>
                <VBtn variant="text" color="error" icon @click="modalClose()">
                    <VIcon size="30">mdi-close</VIcon>
                </VBtn>
            </VCardTitle>

            <VDivider></VDivider>

            <VCardText>

                <VRow>
                    <!-- RIGHT-->
                    <VCol cols="12" sm="12" md="6" lg="6" xl="6">
                        <!--  FORM SOLO PARA LOS CAMPOS BASE-->
                        <VForm ref="form" class="mt-3" v-model="formValid">
                            <VRow>
                                <VCol cols="12">
                                    <VAutocomplete
                                        v-model="juegoModel.loteria_id"
                                        label="Loteria"
                                        placeholder="Seleccione"
                                        prepend-inner-icon="mdi-format-list-checks"
                                        :items="loteriasArr"
                                        item-title="nombre"
                                        item-value="loteria_id"
                                        :rules="juegoForm.texto.rules"
                                    />
                                </VCol>
                                <VCol cols="12">
                                    <VTextField
                                        v-model="juegoModel.nombre"
                                        label="Nombre"
                                        placeholder="Escriba"
                                        prepend-inner-icon="mdi-dice-multiple-outline"
                                        :rules="juegoForm.texto.rules"
                                    />
                                </VCol>
                                <VCol cols="12">
                                    <VTextField
                                        v-model="juegoModel.descripcion"
                                        label="Descripción"
                                        placeholder="Escriba"
                                        prepend-inner-icon="mdi-comment-text-outline"
                                    />
                                </VCol>

                                <VCol cols="12">
                                    <VAutocomplete
                                        v-model="juegoModel.mecanismo_juego_id"
                                        label="Mecanismo del Juego"
                                        placeholder="Seleccione"
                                        prepend-inner-icon="mdi-format-list-checks"
                                        :items="mecanismosJuegosArr"
                                        item-title="nombre"
                                        item-value="mecanismo_juego_id"
                                        density="comfortable"
                                        :rules="juegoForm.texto.rules"
                                    />
                                </VCol>

                                <VCol cols="12">
                                    <VAutocomplete
                                        v-model="juegoModel.dias"
                                        multiple
                                        chips
                                        label="Días de Juego"
                                        placeholder="Seleccione"
                                        prepend-inner-icon="mdi-format-list-checks"
                                        :items="diasArr"
                                        item-title="name"
                                        item-value="id"
                                        density="default"
                                        :rules="juegoForm.texto.rules"
                                    />
                                </VCol>

                            </VRow>
                        </VForm>
                    </VCol>

                    <!-- LEFT-->
                    <VCol cols="12" sm="12" md="6" lg="6" xl="6">
                        <div class="d-flex justify-start">
                            <div cols="6" sm="6" md="6" lg="6" xl="6">
                                <VAvatar rounded="lg" size="100" class="me-6 elevation-3"
                                         :image="juegoModel.avatar"/>
                            </div>
                            <div cols="6" sm="6" md="6" lg="6" xl="6">
                                <VBtn color="info" variant="text" rounded @click="inputLogo?.click()">
                                    <span class="d-sm-block" v-if="juegoModel.juego_id">Cambiar Logo</span>
                                    <span class="d-sm-block" v-else>Elegir Logo</span>
                                </VBtn>

                                <div>  <span class="text-body-1">
                                        Logo en formato JPG, GIF or PNG. Tamaño máximo 800K
                                    </span></div>

                                <input
                                    ref="inputLogo"
                                    type="file"
                                    name="file"
                                    accept=".jpeg,.png,.jpg,GIF"
                                    hidden
                                    @input="changeAvatar">
                            </div>

                        </div>

                        <VCard flat class="mt-3">
                            <VCardTitle class="pa-0 text--info">
                                Horarios del Sorteo
                            </VCardTitle>
                            <VTable fixed-header hover>
                                <thead>
                                <tr>
                                    <th width="50%">Hora</th>
                                    <th width="30%" class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <VTextField
                                            v-model="juegoHoraModel.hora"
                                            type="time"
                                            clearable
                                            solo
                                            :rules="juegoForm.texto.rules"
                                            hide-detals="auto"
                                        />
                                    </td>
                                    <td class="d-flex justify-space-around align-center">
                                        <VBtn color="warning" size="small" variant="text" rounded
                                              @click.stop="juegoHorasReset()">
                                            Reset
                                        </VBtn>
                                        <VBtn color="info" size="small" variant="outlined"
                                              @click.stop="juegoHorasAdd()">
                                            <VIcon>mdi-send-circle-outline</VIcon>
                                        </VBtn>
                                    </td>
                                </tr>
                                <template v-if="juegoHorasArr.length">
                                    <tr v-for="(jh, index) in juegoHorasArr" :key="index">
                                        <template v-if="!Boolean(jh.eliminado)">
                                            <td>
                                                <VTextField
                                                    v-model="jh.hora"
                                                    type="time"
                                                    solo
                                                    :rules="juegoForm.texto.rules"
                                                    hide-detals="auto"
                                                    :disabled="!Boolean(jh.editando)"
                                                />
                                            </td>
                                            <td class="d-flex justify-space-around align-center">
                                                <VBtn color="info" size="small" variant="outlined"
                                                      v-if="!Boolean(jh.editando)"
                                                      @click.stop="juegoHorasEdit(index)">
                                                    <VIcon>mdi-pencil-outline</VIcon>
                                                </VBtn>
                                                <VBtn color="info" size="small" variant="outlined"
                                                      v-if="Boolean(jh.editando)"
                                                      @click.stop="juegoHorasSave(index)">
                                                    <VIcon> mdi-content-save-outline</VIcon>
                                                </VBtn>
                                                <VBtn color="error" size="small" variant="outlined"
                                                      @click.stop="juegoHorasDelete(index)">
                                                    <VIcon>mdi-trash-can-outline</VIcon>
                                                </VBtn>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="text-center text--secondary" colspan="6">
                                            {{ store.mensajes.sinAgregar }}
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </VTable>
                        </VCard>
                    </VCol>

                    <!-- FULL WIDTH-->

                    <VCol cols="12">
                        <VCard flat>
                            <VCardTitle class="pa-0 text--info">
                                Formas de Ganar
                            </VCardTitle>
                            <VTable fixed-header hover>
                                <thead>
                                <tr>
                                    <th width="30%">Modalidad</th>
                                    <th width="20%">Cálculo</th>
                                    <th width="20%">Premio (veces)</th>
                                    <th width="10%">Orden</th>
                                    <th width="10%" class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <VTextField
                                            v-model="juegoFormaGanarModel.modalidad"
                                            :rules="juegoForm.texto.rules"
                                            hide-detals="auto"
                                        />
                                    </td>
                                    <td>
                                        <VSelect
                                            v-model="juegoFormaGanarModel.calculo_jugada_id"
                                            placeholder="Seleccione"
                                            solo
                                            prepend-inner-icon="mdi-format-list-checks"
                                            :items="calculosJugadasArr"
                                            item-value="cat_atributo_id"
                                            item-title="valor1"
                                            :rules="juegoForm.texto.rules"
                                            hide-detals="auto"
                                        />
                                    </td>
                                    <td>
                                        <VTextField
                                            v-model="juegoFormaGanarModel.premio_veces"
                                            type="number"
                                            solo
                                            :rules="juegoForm.texto.rules"
                                            hide-detals="auto"
                                        />
                                    </td>
                                    <td>
                                        <VTextField
                                            v-model="juegoFormaGanarModel.orden_listado"
                                            type="number"
                                            solo
                                            :rules="juegoForm.texto.rules"
                                            hide-detals="auto"
                                        />
                                    </td>
                                    <td class="d-flex justify-space-around align-center">
                                        <VBtn color="warning" size="small" variant="text" rounded
                                              @click.stop="juegoFormasGanarReset()">
                                            Reset
                                        </VBtn>
                                        <VBtn color="info" size="small" variant="outlined"
                                              @click.stop="juegoFormasGanarAdd()">
                                            <VIcon>mdi-send-circle-outline</VIcon>
                                        </VBtn>
                                    </td>
                                </tr>
                                <template v-if="juegoFormasGanarArr.length">
                                    <tr v-for="(jfg, index) in juegoFormasGanarArr" :key="index">
                                        <template v-if="!Boolean(jfg.eliminado)">

                                            <td>
                                                <VTextField
                                                    v-model="jfg.modalidad"
                                                    solo
                                                    :rules="juegoForm.texto.rules"
                                                    hide-detals="auto"
                                                    :disabled="!Boolean(jfg.editando)"
                                                />
                                            </td>
                                            <td>
                                                <VSelect
                                                    v-model="jfg.calculo_jugada_id"
                                                    placeholder="Seleccione"
                                                    solo
                                                    prepend-inner-icon="mdi-format-list-checks"
                                                    :items="calculosJugadasArr"
                                                    item-value="cat_atributo_id"
                                                    item-title="valor1"
                                                    :rules="juegoForm.texto.rules"
                                                    hide-detals="auto"
                                                />
                                            </td>
                                            <td>
                                                <VTextField
                                                    v-model="jfg.premio_veces"
                                                    type="number"
                                                    solo
                                                    :rules="juegoForm.texto.rules"
                                                    hide-detals="auto"
                                                />
                                            </td>
                                            <td>
                                                <VTextField
                                                    v-model="jfg.orden_listado"
                                                    type="number"
                                                    solo
                                                    :rules="juegoForm.texto.rules"
                                                    hide-detals="auto"
                                                />
                                            </td>
                                            <td class="d-flex justify-space-around align-center">
                                                <VBtn color="info" size="small" variant="outlined"
                                                      v-if="!Boolean(jfg.editando)"
                                                      @click.stop="juegoFormasGanarEdit(index)">
                                                    <VIcon>mdi-pencil-outline</VIcon>
                                                </VBtn>
                                                <VBtn color="info" size="small" variant="outlined"
                                                      v-if="Boolean(jfg.editando)"
                                                      @click.stop="juegoFormasGanarSave(index)">
                                                    <VIcon> mdi-content-save-outline</VIcon>
                                                </VBtn>
                                                <VBtn color="error" size="small" variant="outlined"
                                                      @click.stop="juegoFormasGanarDelete(index)">
                                                    <VIcon>mdi-trash-can-outline</VIcon>
                                                </VBtn>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="text-center text--secondary" colspan="6">
                                            {{ store.mensajes.sinAgregar }}
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </VTable>
                        </VCard>
                    </VCol>

                </VRow>

            </VCardText>

            <VDivider></VDivider>

            <VCardActions class="my-2 d-flex justify-space-between">

                <VBtn color="warning" variant="text" rounded @click.stop="resetForm()" :disabled="loading">
                    Reset
                </VBtn>

                <VBtn color="success" rounded variant="flat"
                      :disabled="loading || !formValid || saved"
                      @click.stop="juegoSave()">
                    <VIcon> mdi-content-save-outline</VIcon>
                    <span v-if="juegoModel.juego_id"> Actualizar </span>
                    <span v-else> Guardar</span>
                </VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<style lang="scss" scoped>
</style>
