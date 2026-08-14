<script setup>
const logo = new URL('@images/logo.png', import.meta.url).href
import {useRouter} from "vue-router";

import {toast} from "vue3-toastify";

const TerminosPoliticas = defineAsyncComponent(() => import('@/pages/components/TerminosPoliticas.vue'));

const terminosPoliticas = ref(null);

// Variables y funciones
const router = useRouter();
const store = useAppStore();
const hidePassword = ref(false);
const formValid = ref(false)
const loading = ref(false)

// ESTRUCTURA BASE INICIAL
const personaObj = {
    persona_id: 0,
    nombres: null,
    cat_tipo_persona_id: 4,
    numero_identidad: null,
    celular: null,
    email: null,
    direccion: null,
    usuario_id: 0,
    cat_rol_id: 2,
    usuario: null,
    password: null,
    acepta_terminos_politicas: false,
}

const personaModel = ref(structuredClone(personaObj))

const personaForm = reactive({
    texto: {
        rules: [(v) => !!v || store.validaciones.campoRequerido],
    },
    usuario: {
        rules: [
            (v) => !!v || store.validaciones.campoRequerido,
            (v) => !!v && v.length >= store.app.minCharUser || store.validaciones.campoUser
        ]
    },
    password: {
        rules: [
            (v) => !!v || store.validaciones.campoRequerido,
            (v) => !v || v.length >= store.app.minCharPass || store.validaciones.campoPass,
        ],
    },
    email: {
        rules: [
            (v) => !!v || store.validaciones.campoRequerido,
            (v) => /.+@.+/.test(v) || store.validaciones.campoEmail
        ],
    },
});

const accesoRequerimientos = [
    store.validaciones.campoUser,
    store.validaciones.campoPass
]

const resetForm = () => {
    personaModel.value = structuredClone(personaObj)
}

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
        formData.append('cat_rol_id', personaModel.value.cat_rol_id);
        formData.append('usuario_id', personaModel.value.usuario_id);
        formData.append('usuario', personaModel.value.usuario);
        formData.append('password', personaModel.value.password);

        axios.post('/personas/cliente_store', formData)
            .then((response) => {
                toast.success(response.data.message);

                setTimeout(() => {
                    router.push({name: "Login"});
                }, 6000);

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

const renderTerminosPoliticas = () => {

    if (terminosPoliticas.value) {
        terminosPoliticas.value.modalOpen();
    } else {
        toast.warning('Terminos y Politicas no definidas');
    }
}

</script>

<template>
    <div class="auth-wrapper d-flex align-center justify-center pa-md-4 pa-xs-0">
        <div class="d-flex justify-center ">
            <VCard class="auth-card" :max-width="$vuetify.display.smAndUp ? '60%' : '100%'"
                   :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'">
                <VCardItem class="justify-center">
                    <img :src="logo" alt="Logo" height="80px" class="mx-auto">
                    <h1 class="app-logo-title"> {{ store.app.name }} </h1>
                </VCardItem>

                <VCardTitle>
                    <p class="mb-0">
                        Completa tu registro y que empiece el juego 🚀
                    </p>
                </VCardTitle>

                <VCardText>

                    <VForm ref="form" class="mt-3" v-model="formValid">
                        <VRow>
                            <VCol cols="12">
                                <VDivider>
                                    <span class="mx-4">DATOS PERSONALES</span>
                                </VDivider>
                            </VCol>

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
                                    label="Correo"
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

                            <VCol cols="12">
                                <VDivider>
                                    <span class="mx-1">DATOS ACCESO</span>
                                </VDivider>

                                <ul class="d-flex flex-column gap-y-3">
                                    <li v-for="item in accesoRequerimientos" :key="item" class="d-flex">
                                        <div>
                                            <VIcon size="15" color="info" icon="mdi-cube-outline" class="me-2"/>
                                        </div>
                                        <span class="font-weight-medium">{{ item }}</span>
                                    </li>
                                </ul>
                            </VCol>

                            <VCol cols="12" md="6">
                                <VTextField
                                    v-model="personaModel.usuario"
                                    label="Nick Usuario"
                                    type="text"
                                    placeholder="Escriba su usuario"
                                    prepend-inner-icon="mdi-account-circle-outline"
                                    :rules="personaForm.usuario.rules"
                                />
                            </VCol>

                            <VCol cols="12" md="6">
                                <VTextField
                                    v-model="personaModel.password"
                                    label="Contraseña"
                                    placeholder="Escriba su contraseña"
                                    prepend-inner-icon="mdi-fingerprint"
                                    :type="hidePassword ? 'text' : 'password'"
                                    :append-inner-icon="hidePassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                                    @click:append-inner="hidePassword = !hidePassword"
                                    :rules="personaForm.password.rules"
                                />
                            </VCol>

                            <VCol cols="12" class="d-flex align-center">
                                <VCheckbox
                                    id="terminos-politica"
                                    v-model="personaModel.acepta_terminos_politicas"
                                    inline
                                />
                                <VLabel for="privacy-policy" style="opacity: 1;">
                                    <span class="me-1 text-high-emphasis">ACEPTO</span>
                                    <a @click.stop="renderTerminosPoliticas"
                                       class="text-primary">los Terminos de Servicio y Políticas de Privacidad</a>
                                </VLabel>
                            </VCol>

                            <VCol cols="12" class="d-flex justify-space-between">

                                <VBtn color="warning" variant="text" rounded @click.prevent="resetForm">
                                    Reset
                                </VBtn>

                                <VBtn color="success" rounded :disabled="!formValid" @click.stop="personaSave"
                                      append-icon="mdi-send-circle-outline">
                                    Registrar
                                </VBtn>
                            </VCol>
                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </div>
    </div>

    <TerminosPoliticas ref="terminosPoliticas"/>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
