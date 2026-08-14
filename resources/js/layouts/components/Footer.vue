<script setup>
const anyoActual = Number(new Date().getFullYear());
const anyoInicio = 2024;
let anyoFooter = anyoInicio;
if (anyoActual > anyoInicio) {
    anyoFooter = `${anyoInicio} - ${anyoActual}`
}
</script>
<template>
    <div class="h-100 d-flex align-center justify-md-space-between justify-center">
        <span class="d-flex align-center">
      <VIcon
          icon="mdi-copyright"
          color="info"
          size="1.25rem"
          class="mx-1"
      />
       {{anyoFooter}} | Derechos Reservados
    </span>

    </div>
</template>
