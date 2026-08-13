# Checklist de clonacion funcional

Este documento define como migrar desde `C:\app-mesajeria` hacia Laravel sin perder la logica existente.

## Principio

La fuente de verdad funcional es el sistema actual en React/Expo/Supabase. Laravel debe conservar el mismo comportamiento visible: mismos estados, mismas acciones de admin, mismas reglas por tenant y respuestas claras para el frontend.

## Modulos migrados

- Autenticacion por tenant: login, registro, super admin.
- Tenants y planes: catalogo starter/professional/business, cupos base y usuarios extra.
- Usuarios: listado, creacion, aprobacion, bloqueo, alias, etiquetas y eliminacion.
- Chat: listado por tenant, directos/grupos, envio de mensajes texto/adjuntos por URL.
- Lectura: marcador `chat_read_markers` para checks y no leidos.
- Limpieza admin: `admin_chat_clears` para vaciar historial visual de un admin sin borrar el chat global.
- Biblioteca: respuestas rapidas, imagenes guardadas, alta y baja.
- Anuncios: alta admin y consumo publico por tenant.
- Loterias: loterias, sorteos y resultados base.

## Modulos pendientes de paridad completa

- Storage propio para carga real de imagenes/audio/archivos desde admin y movil.
- Notificaciones push FCM/Expo desde Laravel.
- Estados/publicaciones tipo historias.
- Reenvio, fijado, destacado y copia avanzada de mensajes.
- Reportes operativos de loteria.
- Ventas/apartados de numeros, pagos, cierres, ganadores y comisiones.
- Panel web Laravel o frontend React conectado al nuevo contrato.

## Regla de avance

Antes de agregar multitenant avanzado o nuevas funciones comerciales, cada modulo se porta con:

- migracion,
- modelo,
- endpoint,
- validacion de tenant,
- validacion de rol,
- prueba de contrato,
- registro en este checklist.

