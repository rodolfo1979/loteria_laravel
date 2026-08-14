<script setup>

import {useAppStore} from '@/plugins/store/appStore.js';
import {toast} from "vue3-toastify";

const store = useAppStore();

const modal = reactive({
    open: false,
    title: null,
});

let personaObj = {
    persona_id: 0,
    nombres: null,
    cat_tipo_persona_id: null,
    numero_identidad: null,
    celular: null,
    email: null,
    direccion: null,
    activo: true,
}

const formValid = ref(true);
const loading = ref(false);
const saved = ref(false);
const personaModel = ref(structuredClone(personaObj));

const emit = defineEmits(["getpersonas"]);

const personaForm = reactive({
    texto: {
        rules: [(v) => !!v || store.validaciones.campoRequerido],
    },
});

const modalOpen = async (personaId = 0, tipoPersonaId = 4, tipoPersona = "Cliente") => {
    modal.open = true;

    modal.title = Number(personaId) ? 'Editar ' + tipoPersona : 'Nuevo ' + tipoPersona;
    personaObj.persona_id = personaId;
    personaObj.cat_tipo_persona_id = tipoPersonaId;
    personaModel.value = structuredClone(personaObj)
    saved.value = false;
}

const personaSave = () => {
    if (formValid.value) {
        loading.value = true;

        axios.post('/personas/save', personaModel.value)
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
        emit("getPersonas");
    }
}

const resetForm = () => {
    modal.open = false;
}
// Exponer el método modalOpen para que pueda ser llamado desde el padre
defineExpose({
    modalOpen
});

</script>

<template>
    <VDialog persistent width="60%" max-width="1200" transition="dialog-bottom-transition" :fullscreen="$vuetify.display.mdAndDown"
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
                <VForm ref="form" class="mt-3" v-model="formValid">

                    <VRow>
                        <VCol md="6" cols="12">
                            <VTextField
                                v-model="personaModel.numero_identidad"
                                placeholder="Escriba"
                                label="Número de identidad"
                                prepend-inner-icon="mdi-card-account-details-outline"
                                :rules="personaForm.texto.rules"
                            />
                        </VCol>

                        <VCol md="6" cols="12">
                            <VTextField
                                v-model="personaModel.nombres"
                                placeholder="Escriba"
                                label="Nombres y Apellidos"
                                prepend-inner-icon="mdi-account-outline"
                                :rules="personaForm.texto.rules"
                            />
                        </VCol>

                        <VCol cols="12" md="6">
                            <VTextField
                                v-model="personaModel.celular"
                                label="Celular/Whastsapp"
                                placeholder="Escriba"
                                prepend-inner-icon="mdi-cellphone-sound"
                            />
                        </VCol>

                        <VCol cols="12" md="6">
                            <VTextField
                                v-model="personaModel.email"
                                label="Correo"
                                placeholder="Escriba"
                                type="email"
                                prepend-inner-icon="mdi-email-outline"
                            />
                        </VCol>

                        <VCol cols="12">
                            <VTextarea
                                v-model="personaModel.direccion"
                                label="Dirección"
                                placeholder="Escriba"
                                prepend-inner-icon="mdi-map-marker-outline"
                                rows="2"
                                autogrow
                            />
                        </VCol>
                    </VRow>
                </VForm>
            </VCardText>

            <VDivider></VDivider>

            <VCardActions class="my-2 d-flex justify-space-between">

                <VBtn color="warning" variant="text" rounded @click.stop="resetForm()" :disabled="loading">
                    Reset
                </VBtn>

                <VBtn
                    color="success"
                    rounded
                    :disabled="loading || !formValid || saved"
                    variant="flat"
                    @click.stop="personaSave()">
                    <span v-if="personaModel.persona_id"> Actualizar </span>
                    <span v-else> Registrar</span>
                    <VIcon>mdi-send-circle-outline</VIcon>
                </VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<style lang="scss" scoped>
</style>
