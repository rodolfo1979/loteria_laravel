<script setup>
import {useRouter} from 'vue-router';
import {toast} from 'vue3-toastify';
import {useAppStore} from '@/plugins/store/appStore.js';
import Ls from '@/utils/Ls.js';
const logo = new URL('@images/logo.png', import.meta.url).href

// Variables y funciones
const router = useRouter();
const store = useAppStore();
const formValid = ref(true);
const loading = ref(false);
const hidePassword = ref(false);

const usuarioModel = reactive({
    usuario: null,
    password: null,
});

const usuarioForm = reactive({
    usuario: {
        rules: [(v) => !!v || store.mensajes.campoRequerido],
    },
    password: {
        rules: [(v) => !!v || store.mensajes.campoRequerido],
    },
});

const login = () => {
    if (formValid.value) {
        loading.value = true;

        axios.post('/auth/login', usuarioModel)
            .then(async (response) => {
                if (response.data.status === 'active') {
                    const persona = response.data.data;

                    Ls.set('token', response.data.token);
                    Ls.set('nombres', persona.nombres);
                    Ls.set('foto', persona.foto);

                    await router.push({name: 'Dashboard'});
                } else {
                    toast.error(response.data.message);
                }
            }).catch((error) => {
            console.log(error);
            toast.warning('No se pudo conectar');
        }).finally(() => {
            loading.value = false;
        });
    } else {
        toast.warning('Complete la información requerida');
    }
};

onMounted(() => {
    if (Ls.get('token')) {
        router.push({name: 'Dashboard'});
    }
});
</script>

<template>
    <div class="auth-wrapper d-flex align-center justify-center pa-4">
        <div class="position-relative my-sm-16">

            <VCard class="auth-card" max-width="460" :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'">
                <VCardItem class="justify-center">
                    <img :src="logo" alt="Logo" height="80px" class="mx-auto">
                    <h1 class="app-logo-title"> {{ store.app.name }} </h1>
                </VCardItem>

                <VCardText>
                    <VForm ref="form" v-model="formValid">
                        <VRow>
                            <VCol cols="12">
                                <VTextField
                                    v-model="usuarioModel.usuario"
                                    autofocus
                                    label="Nick Usuario"
                                    type="text"
                                    placeholder="Escriba su usuario"
                                    prepend-inner-icon="mdi-account-circle-outline"
                                    :rules="usuarioForm.usuario.rules"
                                />
                            </VCol>

                            <VCol cols="12">
                                <VTextField
                                    v-model="usuarioModel.password"
                                    label="Contraseña"
                                    placeholder="Escriba su contraseña"
                                    prepend-inner-icon="mdi-fingerprint"
                                    :type="hidePassword ? 'text' : 'password'"
                                    :append-inner-icon="hidePassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                                    @click:append-inner="hidePassword = !hidePassword"
                                    :rules="usuarioForm.usuario.rules"
                                />
                            </VCol>

                            <VCol cols="12">
                                <VBtn block rounded :disabled="!formValid" @click.stop="login"
                                      append-icon="mdi-chevron-right-circle-outline"
                                      :loading="loading">
                                    Ingresar
                                </VBtn>
                            </VCol>

                            <!-- create account -->
                            <VCol cols="12" class="text-body-1 text-center">

                                <!-- remember me checkbox -->
                                <div class="d-flex align-center justify-space-between flex-wrap my-3">

                                    <a class="text-primary" href="javascript:void(0)">
                                        Olvide mis datos de Acceso
                                    </a>
                                </div>

                                <span class="d-inline-block">
                                 ¿Eres nuevo visitante?
                                </span>
                                <VBtn rounded variant="text" to="/registro">
                                    Crear mi cuenta
                                </VBtn>
                            </VCol>

                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </div>
    </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
