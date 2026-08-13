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

Login:

```json
{
  "identifier": "cliente@negocio.com",
  "password": "secret123"
}
```

Registro:

```json
{
  "fullName": "Cliente Demo",
  "email": "cliente@negocio.com",
  "phone": "50660000000",
  "password": "secret123"
}
```

## App/Admin

- `GET /me`
- `GET /chats`
- `POST /chats`
- `POST /messages`
- `POST /messages/mark-read`
- `GET /announcements`

Enviar mensaje:

```json
{
  "chatId": "uuid",
  "body": "Hola",
  "messageType": "text"
}
```

## Admin tenant

- `GET /admin/users`
- `POST /admin/users`
- `PATCH /admin/users/{user}`
- `DELETE /admin/users/{user}`
- `GET /admin/library`
- `POST /admin/library`
- `GET /admin/announcements`
- `POST /admin/announcements`
- `GET /admin/lotteries`
- `POST /admin/lotteries`
- `PATCH /admin/lotteries/{lottery}`
- `DELETE /admin/lotteries/{lottery}`
- `POST /admin/lotteries/{lottery}/draws`
- `PATCH /admin/lottery-draws/{draw}`
- `POST /admin/lottery-draws/{draw}/results`

## Super Admin

- `POST /super-admin/auth/login`
- `GET /super-admin/tenants`
- `POST /super-admin/tenants`
- `PATCH /super-admin/tenants/{tenant}`
- `POST /super-admin/tenants/{tenant}/reset-admin-password`
