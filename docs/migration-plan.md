# Plan de migracion a Laravel

## Principio

La migracion se hace en paralelo. La app actual sigue funcionando mientras Laravel replica la API y luego se cambia el `EXPO_PUBLIC_API_BASE_URL`.

## Arquitectura recomendada

- Laravel como backend API.
- PostgreSQL/Neon como base unica.
- Sanctum para sesiones web/admin.
- Token personal o JWT corto para app movil.
- Laravel Queues para push, IA, envio masivo y tareas pesadas.
- Laravel Reverb/WebSockets para tiempo real.
- Storage S3-compatible para imagenes, audios y archivos.
- Inertia + React para panel admin si se decide unificar backend/frontend.

## Orden de migracion

1. Esquema y seed comercial.
2. Auth y tenants.
3. API de bootstrap/login.
4. Chats y mensajes.
5. Admin usuarios/anuncios/biblioteca.
6. Notificaciones push.
7. IA por tenant.
8. Storage.
9. WebSockets.
10. Corte final de dominio/API.

## Reglas de negocio que se conservan

- Un APK comun LotoX Business.
- Cada request envia `x-lotox-tenant`.
- Super admin crea tenants y administra plan/extras.
- Usuarios `blocked` no consumen cupo.
- `super_admin` no consume cupo.
- Limite efectivo: `plan.user_limit + tenant.extra_user_slots`.

