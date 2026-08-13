# Contrato API inicial

Todas las rutas usan prefijo `/api/v1`.

Headers:

```http
x-lotox-tenant: slug-del-negocio
Authorization: Bearer TOKEN
Accept: application/json
```

## Publico

- `GET /bootstrap?tenant=slug`
- `POST /auth/login`
- `POST /auth/register`

## App/Admin

- `GET /me`
- `GET /chats`
- `POST /chats`
- `GET /chats/{chat}/messages`
- `POST /messages`
- `POST /messages/mark-read`
- `GET /announcements`

## Admin tenant

- `GET /admin/users`
- `POST /admin/users`
- `PATCH /admin/users/{user}`
- `DELETE /admin/users/{user}`
- `GET /admin/library`
- `POST /admin/library`
- `GET /admin/announcements`
- `POST /admin/announcements`

## Super Admin

- `GET /super-admin/tenants`
- `POST /super-admin/tenants`
- `PATCH /super-admin/tenants/{tenant}`
- `POST /super-admin/tenants/{tenant}/reset-admin-password`

