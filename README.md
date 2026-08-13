# LotoX Laravel Migration

Backend Laravel/PHP para migrar LotoX Business sin detener la app actual.

## Estado

Este paquete es la base de migracion. Define la arquitectura, migraciones PostgreSQL/Neon, modelos, servicios y rutas API principales. No reemplaza todavia la app Expo/React; se levanta en paralelo y luego la app movil apunta a esta API.

## Requisitos

- PHP 8.3+
- Composer
- PostgreSQL/Neon
- Node.js solo si se agrega frontend Inertia/React

## Instalacion sugerida

```bash
composer create-project laravel/laravel lotox-laravel-runtime
```

La base esta pensada para Laravel 13, que es la linea actual de 2026.

Luego copiar estas carpetas dentro del proyecto Laravel real:

- `app/`
- `database/`
- `routes/`
- `docs/`

Leer tambien `docs/runtime-integration.md` para registrar middleware, Sanctum y el modelo autenticable.

Variables `.env` principales:

```env
APP_NAME="LotoX Business"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://USER:PASSWORD@HOST:5432/DB?sslmode=require

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173,lotox.vercel.app
SESSION_DOMAIN=localhost
```

Credenciales demo locales despues de ejecutar `php artisan db:seed`:

- Super admin: `superadmin@lotox.local` / `SuperAdmin123`
- Admin tenant demo: `admin@demo.local` / `AdminDemo123`
- Cliente demo: `cliente@demo.local` / `ClienteDemo123`
- Tenant header: `x-lotox-tenant: demo`

Si aparece `no such table: sessions`, ejecuta:

```bash
php artisan migrate
```

El proyecto incluye migraciones para `sessions`, `cache`, `jobs` y tokens de Sanctum.

## Modulos incluidos

- Multitenant por `tenant_slug`.
- Planes comerciales y limites de usuarios.
- Auth base para super admin, admin y cliente.
- Chats, miembros y mensajes.
- Anuncios, respuestas rapidas y biblioteca.
- Administracion de loterias, sorteos, horarios y resultados.
- Servicio central `TenantCapacityService`.
- API inicial compatible con `x-lotox-tenant`.

## Fases

1. Crear Laravel real con Composer.
2. Copiar este paquete.
3. Ejecutar migraciones.
4. Implementar Sanctum/JWT segun decision final.
5. Adaptar app movil Expo para consumir `/api/v1`.
6. Migrar admin web a Inertia React o mantener React separado.
