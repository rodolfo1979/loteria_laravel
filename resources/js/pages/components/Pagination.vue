<script setup>
const props = defineProps({
    pagination: {
        type: Object,
        required: true,
        default: () => ({
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0,
        }),
    },
    arrayRowsPages: {
        type: Array,
        required: true,
    },
});

// Variables reactivas
const rowsPage = ref(props.arrayRowsPages[0]);
const currentPage = ref(1);
const emit = defineEmits(["changePage"]);

// Computed para calcular los números de las páginas
const pagesNumber = computed(() => {
    let from = 1;
    let to = props.pagination.last_page;

    let pagesArray = [];
    while (from <= to) {
        pagesArray.push(from);
        from++;
    }
    return pagesArray;
});

// Métodos
const changePage = (page) => {
    currentPage.value = page;
    const object = {
        page: page,
        rowsPage: rowsPage.value,
    };
    emit("changePage", object); // Emitimos el evento al padre
};
</script>

<style lang="scss" scoped>
.VSelect {
    &.fit {
        width: min-content;
    }
}
</style>
<template>
    <VRow class="mt-3" v-if="pagination.total >= 1" no-gutters>
        <VCol cols="12" sm="12" md="5" lg="5" xl="5" class="text-left mt-1">
            <div class="font-weight-bold">
                Total Registros: {{ $filters.numero(pagination.total) }} | Mostrando del
                {{ $filters.numero(pagination.from) }} al {{ $filters.numero(pagination.to) }}
            </div>
        </VCol>
        <VCol cols="12" sm="12" md="7" lg="7" xl="7" class="py-1 text-right">
            <!--SELECT ROWS-->
            <VBtn color="info" variant="text" class="ma-0 pa-0">
                <VSelect
                    class="ma-0"
                    v-model="rowsPage"
                    :items="arrayRowsPages"
                    @update:modelValue="changePage(1)">
                    <template v-slot:prepend>
                        <label class="pt-0">Filas</label>
                    </template>
                </VSelect>
            </VBtn>

            <!--FIRST-->
            <VBtn color="info" variant="outlined" icon @click.prevent="changePage(1)" class="ma-0"
                  :disabled="pagination.current_page === 1">
                <VIcon>mdi-chevron-double-left</VIcon>
            </VBtn>

            <!--BEFORE-->
            <VBtn color="info" variant="outlined" icon class="ma-0"
                  @click.prevent="changePage(pagination.current_page - 1)"
                  :disabled="pagination.current_page === 1">
                <VIcon>mdi-chevron-left</VIcon>
            </VBtn>

            <!--SELECT NUMBER-->
            <VBtn color="info" variant="text" class="ma-0 pa-0">
                <VSelect
                    class="ma-0"
                    v-model="currentPage"
                    :items="pagesNumber"
                    @update:modelValue="changePage(currentPage)">
                </VSelect>
            </VBtn>

            <!--NEXT-->
            <VBtn color="info" variant="outlined" icon class="ma-0"
                  @click.prevent="changePage(pagination.current_page + 1)"
                  :disabled="pagination.current_page >= pagination.last_page">
                <VIcon>mdi-chevron-right</VIcon>
            </VBtn>

            <!--LAST-->
            <VBtn color="info"  variant="outlined" icon class="ma-0"
                  @click.prevent="changePage(pagination.last_page)"
                  :disabled="pagination.current_page >= pagination.last_page">
                <VIcon>mdi-chevron-double-right</VIcon>
            </VBtn>
        </VCol>
    </VRow>
</template>
