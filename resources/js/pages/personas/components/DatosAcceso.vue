<script setup>

const store = useAppStore();

const isCurrentPasswordVisible = ref(false)
const isNewPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const currentPassword = ref('12345678')
const newPassword = ref('87654321')
const confirmPassword = ref('87654321')

const accesoRequerimientos = [
    store.validaciones.campoUser,
    store.validaciones.campoPass
]

const recentDevicesHeaders = [
    {
        title: 'Navegador',
        key: 'browser',
    },
    {
        title: 'Dispositivo',
        key: 'device',
    },
    {
        title: 'Ciudad',
        key: 'location',
    },
    {
        title: 'Fecha',
        key: 'recentActivity',
    },
]

const recentDevices = [
    {
        browser: 'Chrome on Windows',
        device: 'HP Spectre 360',
        location: 'Lberia, CR',
        recentActivity: '20/08/2024, 10:20 am',
        deviceIcon: {
            icon: 'bxl-windows',
            color: 'primary',
        },
    },
    {
        browser: 'Chrome on iPhone',
        device: 'iPhone 12x',
        location: 'Alajuela, CR',
        recentActivity: '20/07/2024, 10:20 am',
        deviceIcon: {
            icon: 'mdi-mobile',
            color: 'error',
        },
    },
    {
        browser: 'Chrome on Android',
        device: 'Oneplus 9 Pro',
        location: 'Puerto Limón, CR',
        recentActivity: '20/06/2024, 10:20 am',
        deviceIcon: {
            icon: 'bxl-android',
            color: 'success',
        },
    },
    {
        browser: 'Chrome on macOS',
        device: 'Apple iMac',
        location: 'Guanacaste, CR',
        recentActivity: '20/05/2024, 10:20 am',
        deviceIcon: {
            icon: 'bxl-apple',
            color: 'secondary',
        },
    },
    {
        browser: 'Chrome on Windows',
        device: 'HP Spectre 360',
        location: 'Cartago, CR',
        recentActivity: '20/04/2024, 10:20 am',
        deviceIcon: {
            icon: 'bxl-windows',
            color: 'primary',
        },
    },
    {
        browser: 'Chrome on Android',
        device: 'Oneplus 9 Pro',
        location: 'San José, CR',
        recentActivity: '20/03/2024, 10:20 am',
        deviceIcon: {
            icon: 'bxl-android',
            color: 'success',
        },
    },
    {
        browser: 'Chrome on Android',
        device: 'Samsung S24plus',
        location: 'San José, CR',
        recentActivity: '19/03/2024, 09:35 pm',
        deviceIcon: {
            icon: 'bxl-android',
            color: 'success',
        },
    },
]
</script>

<template>
    <VRow>
        <!-- CAMBIAR CONTRASEÑA -->
        <VCol cols="12">
            <VCard title="Actualizar contraseña">
                <VForm>
                    <VCardText>

                        <VRow>

                            <VCol cols="12">
                                <ul class="d-flex flex-column gap-y-3">
                                    <li v-for="item in accesoRequerimientos" :key="item" class="d-flex">
                                        <div>
                                            <VIcon size="15" color="info" icon="mdi-cube-outline" class="me-2"/>
                                        </div>
                                        <span class="font-weight-medium">{{ item }}</span>
                                    </li>
                                </ul>
                            </VCol>

                            <VCol
                                cols="12"
                                md="6"
                            >
                                <!--current password -->
                                <VTextField
                                    v-model="currentPassword"
                                    :type="isCurrentPasswordVisible ? 'text' : 'password'"
                                    :append-inner-icon="isCurrentPasswordVisible ? 'mdi-hide' : 'mdi-show'"
                                    label="Contraseña actual"
                                    placeholder="············"
                                    @click:append-inner="isCurrentPasswordVisible = !isCurrentPasswordVisible"
                                />
                            </VCol>
                        </VRow>

                        <!--New Password -->
                        <VRow>
                            <VCol
                                cols="12"
                                md="6"
                            >
                                <!--new password -->
                                <VTextField
                                    v-model="newPassword"
                                    :type="isNewPasswordVisible ? 'text' : 'password'"
                                    :append-inner-icon="isNewPasswordVisible ? 'mdi-hide' : 'mdi-show'"
                                    label="Nueva contraseña"
                                    autocomplete="on"
                                    placeholder="············"
                                    @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                                />
                            </VCol>

                            <VCol
                                cols="12"
                                md="6"
                            >
                                <!--confirm password -->
                                <VTextField
                                    v-model="confirmPassword"
                                    :type="isConfirmPasswordVisible ? 'text' : 'password'"
                                    :append-inner-icon="isConfirmPasswordVisible ? 'mdi-hide' : 'mdi-show'"
                                    label="Confirmar nueva contraseña"
                                    placeholder="············"
                                    @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                                />
                            </VCol>
                        </VRow>
                    </VCardText>

                    <!--Action Buttons -->
                    <VCardText class="d-flex justify-space-between">
                        <VBtn rounded color="success">Guardar</VBtn>

                        <VBtn color="warning" variant="text" rounded>
                            Reset
                        </VBtn>
                    </VCardText>
                </VForm>
            </VCard>
        </VCol>
        <!-- !SECTION -->

        <!-- SECTION Two-steps verification -->
        <VCol cols="12">
            <VCard title="Autenticación Two-Factor">
                <VCardText>
                    <p class="font-weight-semibold">
                        Actualmente en desarrollo.
                    </p>
                    <p>
                        La autenticación de Dos-Pasos agregar una capa de seguridad a su cuenta
                    </p>
                    <p>
                        Cada que inicie sesión se enviará un codigo de acceso a su celular o correo electrónico.
                    </p>

                    <VBtn color="success" rounded>
                        Activar Two-Factor
                    </VBtn>
                </VCardText>
            </VCard>
        </VCol>
        <!-- !SECTION -->

        <!-- SECTION Recent Devices -->
        <VCol cols="12">
            <!--Table -->
            <VCard title="Tus últimas conexiones">
                <VDataTable :headers="recentDevicesHeaders" :items="recentDevices"
                            class="text-no-wrap rounded-0 text-sm">
                    <template #item.browser="{ item }">
                        <div class="d-flex">
                            <VIcon start :icon="item.deviceIcon.icon" :color="item.deviceIcon.color"/>
                            <span class="text-high-emphasis text-base">
                            {{ item.browser }}
                          </span>
                        </div>
                    </template>
                    <template #bottom/>
                </VDataTable>
            </VCard>
        </VCol>
        <!-- !SECTION -->
    </VRow>
</template>
