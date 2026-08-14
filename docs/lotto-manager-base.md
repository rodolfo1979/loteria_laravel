# Base funcional: Lotto Manager

El sistema de administracion de loterias tomado como fuente es:

`C:\Repocitorio\lotto_manager`

Repositorio original detectado:

`https://github.com/hdesarrollo/lotto_manager.git`

## Modulos importados

- Agencias
- Personas/clientes/vendedores
- Usuarios, roles, permisos, paginas y acciones
- Loterias
- Juegos
- Dias, horas y formas de ganar
- Ventas y detalles de venta
- Reporte de proximos sorteos
- Impresion de tickets

## Adaptacion aplicada

- Las migraciones originales fueron copiadas al Laravel nuevo conservando nombres de tablas y orden.
- Se agregaron macros de auditoria compatibles con Laravel 13:
  - `estadosAuditoria`
  - `usuariosAuditoria`
  - `fechasAuditoria`
- La migracion `ALTER SEQUENCE personas_persona_id_seq` se conserva solo para PostgreSQL y se omite en SQLite local.
- Los modelos importados ahora usan Sanctum en vez de Passport.
- El login de `usuarios` genera token Sanctum y conserva el contrato original:
  - `status`
  - `message`
  - `token`
  - `data`
- Las rutas originales quedaron expuestas bajo:
  - `/api/v1/lotto/...`

## Pendiente tecnico inmediato

- Confirmar la contrasena original de los usuarios sembrados `developer` y `gestor`, o definir una semilla local clara.
- Instalar o sustituir dependencias operativas usadas por el sistema base:
  - `mpdf/mpdf` para tickets PDF.
  - `maatwebsite/excel` para exportes.
  - `jenssegers/agent` para deteccion de navegador/dispositivo.
- Migrar el frontend Vue 3/Vuetify del sistema base y ajustar su base URL a `/api/v1/lotto`.
- Integrar multitenant sobre estas tablas sin alterar la logica de venta original.

