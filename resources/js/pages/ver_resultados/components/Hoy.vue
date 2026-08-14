<script setup>

const tab = ref(null)

const tabItems = [
    'Ganadores hoy',
    'Historial',
]

</script>

<template>
    <VCard>

        <VCardItem>
            <VCardTitle>Números ganadores de los Sorteos</VCardTitle>
        </VCardItem>

        <VCardText>
            <VCard>
                <VCardTitle>
                    Nombre de la Loteria y Sorteo
                </VCardTitle>
                <VCardText>
                    Numeros ganadores
                </VCardText>
            </VCard>
        </VCardText>
    </VCard>

</template>
