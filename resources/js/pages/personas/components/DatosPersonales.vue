<script setup>
const avatarDefault = new URL('@public/images/personas/fotos/default.jpg', import.meta.url).href;

import {toast} from "vue3-toastify";
import {useAppStore} from '@/plugins/store/appStore.js';
import {useRouter} from "vue-router";

const router = useRouter();
const store = useAppStore();

let personaObj = {
    avatar: avatarDefault,
    foto: null,
    persona_id: null,
    nombres: null,
    numero_identidad: null,
    email: null,
    celular: null,
    direccion: null,
}

const inputFoto = ref()
const formValid = ref(true);
const loading = ref(false);
const personaModel = ref(structuredClone(personaObj))

const personaForm = reactive({
    texto: {
        rules: [(v) => !!v || store.validaciones.campoRequerido],
    },
    email: {
        rules: [
            (v) => !!v || store.validaciones.campoRequerido,
            (v) => /.+@.+/.test(v) || store.validaciones.campoEmail
        ],
    },
});

const changeAvatar = file => {
    const fileReader = new FileReader()
    const {files} = file.target

    if (files && files.length) {
        fileReader.readAsDataURL(files[0])

        // Guardamos el archivo seleccionado
        personaModel.value.foto = files[0];
        fileReader.onload = () => {
            if (typeof fileReader.result === 'string')
                personaModel.value.avatar = fileReader.result
        }
    }
}

const loadAvatar = (avatarName) => {
    personaModel.value.avatar = avatarName;
    personaObj.avatar = avatarName;
};

const getInfo = () => {

    axios.get('/personas/info')
        .then((response) => {
            personaObj = response.data.data;
            personaModel.value = structuredClone(personaObj)

            // CALL PICTURE
            loadAvatar(personaModel.value.foto);

        }).catch((error) => {
        console.log(error);
        toast.warning('No se pudo obtener los Datos');
    }).finally(() => {
        loading.value = false;
    });
};

const personaSave = () => {
    if (formValid.value) {
        loading.value = true;

        const formData = new FormData();

        // ADD DATA FormData
        formData.append('persona_id', personaModel.value.persona_id);
        formData.append('nombres', personaModel.value.nombres);
        formData.append('numero_identidad', personaModel.value.numero_identidad);
        formData.append('email', personaModel.value.email);
        formData.append('celular', personaModel.value.celular);
        formData.append('direccion', personaModel.value.direccion);
        formData.append('foto', personaModel.value.foto);

        axios.post('/personas/save', formData)
            .then((response) => {
                toast.success(response.data.message);

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

const resetForm = () => {
    if (Number(personaModel.value.persona_id)) {
        getInfo();
    } else {
        personaModel.value = structuredClone(personaObj)
    }
}

const resetAvatar = () => {
    personaModel.avatar = personaObj.avatar
}

onMounted(() => {
    getInfo();
});

</script>

<template>
    <VRow>
        <VCol cols="12">
            <VCard title="Datos personales">

                <VCardText class="d-flex">
                    <!-- FOTO -->
                    <VAvatar rounded="lg" size="100" class="me-6 elevation-3" :image="personaModel.avatar"/>

                    <!-- CARGAR FOTO -->
                    <form class="d-flex flex-column justify-center gap-5">
                        <div class="d-flex justify-space-between">
                            <VBtn color="info" variant="text" rounded @click="inputFoto?.click()">
                                <span class="d-sm-block">Cambiar foto</span>
                            </VBtn>

                            <input
                                ref="inputFoto"
                                type="file"
                                name="file"
                                accept=".jpeg,.png,.jpg,GIF"
                                hidden
                                @input="changeAvatar">

                            <VBtn color="warning" variant="text" rounded @click="resetAvatar" hidden="hidden">
                                <span class="d-none d-sm-block me-1">Reset</span>
                            </VBtn>
                        </div>

                        <p class="text-body-1 mb-0">
                            Foto en formato JPG, GIF or PNG. Tamaño máximo 800K
                        </p>
                    </form>
                </VCardText>

                <VDivider/>

                <VCardText>
                    <!--Form -->
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
                                    :rules="personaForm.texto.rules"
                                />
                            </VCol>

                            <VCol cols="12" md="6">
                                <VTextField
                                    v-model="personaModel.email"
                                    label="Email"
                                    placeholder="Escriba"
                                    type="email"
                                    prepend-inner-icon="mdi-email-outline"
                                    :rules="personaForm.email.rules"
                                />
                            </VCol>

                            <VCol cols="12">
                                <VTextarea
                                    v-model="personaModel.direccion"
                                    label="Dirección"
                                    placeholder="Escriba"
                                    prepend-inner-icon="mdi-map-marker-outline"
                                    :rules="personaForm.texto.rules"
                                    rows="2"
                                    autogrow
                                />
                            </VCol>

                            <VCol cols="12" class="d-flex justify-space-between">
                                <VBtn color="warning" variant="text" rounded @click.prevent="resetForm">
                                    Reset
                                </VBtn>

                                <VBtn color="success" rounded :disabled="!formValid" @click.stop="personaSave"
                                      prepend-icon="mdi-content-save-outline">
                                    Actualizar
                                </VBtn>

                            </VCol>
                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </VCol>

    </VRow>
</template>
