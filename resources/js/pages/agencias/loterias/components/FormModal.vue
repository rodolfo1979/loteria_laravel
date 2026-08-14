<script setup>
const avatarDefault = new URL('@public/images/loterias/logos/default.png', import.meta.url).href;

import {toast} from "vue3-toastify";

const store = useAppStore();

const modal = reactive({
    open: false,
    title: null,
});

let loteriaObj = {
    loteria_id: 0,
    nombre: null,
    descripcion: null,
    logo: null,
    avatar: avatarDefault,
    activo: true,
}

const inputLogo = ref()
const formValid = ref(true);
const loading = ref(false);
const saved = ref(false);
const loteriaModel = ref(structuredClone(loteriaObj))
const emit = defineEmits(["getLoterias"]);

const loteriaForm = reactive({
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
        loteriaModel.value.logo = files[0];
        fileReader.onload = () => {
            if (typeof fileReader.result === 'string')
                loteriaModel.value.avatar = fileReader.result
        }
    }
}

const loadAvatar = (avatarName) => {
    loteriaModel.value.avatar = avatarName;
    loteriaObj.avatar = avatarName;
};

const modalOpen = async (loteriaId) => {
    modal.open = true;
    modal.title = Number(loteriaId) ? "Editar Loteria" : "Nueva Lotería";
    loteriaObj.loteria_id = loteriaId;
    loteriaModel.value = structuredClone(loteriaObj)
    loadAvatar(avatarDefault);
    saved.value = false;
    await getEdit();
}

// GET TO EDIT
const getEdit = async () => {
    if (loteriaModel.value.loteria_id) {
        let params = {loteria_id: loteriaModel.value.loteria_id}
        await axios.get("/loterias/edit", {params})
            .then((response) => {

                loteriaModel.value = response.data.data[0]

                // CALL PICTURE
                loadAvatar(loteriaModel.value.logo);

            }).catch((error) => {
                console.log(error);
                toast.warning('No se pudo obtener los Datos');
            }).finally(() => {
                loading.value = false;
            });
    }
};

const loteriaSave = () => {
    if (formValid.value) {
        loading.value = true;

        const formData = new FormData();

        // ADD DATA FormData
        formData.append('loteria_id', loteriaModel.value.loteria_id ?? 0);
        formData.append('nombre', loteriaModel.value.nombre);
        formData.append('descripcion', loteriaModel.value.descripcion);
        formData.append('logo', loteriaModel.value.logo);

        axios.post('/loterias/save', formData)
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
        emit("getLoterias");
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
    <VDialog persistent width="60%" max-width="1200"
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
                <VForm ref="form" class="mt-3" v-model="formValid">
                    <VRow>
                        <VCol cols="12" sm="12" md="6" lg="6" xl="6">

                            <VRow>
                                <VCol cols="12">
                                    <VTextField
                                        v-model="loteriaModel.nombre"
                                        label="Nombre"
                                        placeholder="Escriba"
                                        prepend-inner-icon="mdi-dice-multiple-outline"
                                        :rules="loteriaForm.texto.rules"
                                    />
                                </VCol>
                                <VCol cols="12">
                                    <VTextField
                                        v-model="loteriaModel.descripcion"
                                        label="Descripción"
                                        placeholder="Escriba"
                                        prepend-inner-icon="mdi-comment-text-outline"
                                        :rules="loteriaForm.texto.rules"
                                    />
                                </VCol>
                            </VRow>

                        </VCol>

                        <VCol cols="12" sm="12" md="6" lg="6" xl="6">

                            <div class="d-flex flex-wrap gap-2">
                                <VAvatar rounded="lg" size="100" class="me-6 elevation-3" :image="loteriaModel.avatar"/>

                                <VBtn color="info" variant="text" rounded @click="inputLogo?.click()">
                                    <span class="d-sm-block" v-if="loteriaModel.loteria_id">Cambiar Logo</span>
                                    <span class="d-sm-block" v-else>Elegir Logo</span>
                                </VBtn>

                                <p class="text-body-1">
                                    Logo en formato JPG, GIF or PNG. Tamaño máximo 800K
                                </p>

                                <input
                                    ref="inputLogo"
                                    type="file"
                                    name="file"
                                    accept=".jpeg,.png,.jpg,GIF"
                                    hidden
                                    @input="changeAvatar">
                            </div>
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
                    @click.stop="loteriaSave()">
                    <VIcon> mdi-content-save-outline</VIcon>
                    <span v-if="loteriaModel.loteria_id"> Actualizar </span>
                    <span v-else> Guardar</span>
                </VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<style lang="scss" scoped>
</style>
