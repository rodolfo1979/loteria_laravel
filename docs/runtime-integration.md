# Integracion en un Laravel 13 real

Este paquete contiene la logica inicial. Para hacerlo ejecutable:

```bash
composer create-project laravel/laravel lotox-laravel-runtime
```

Copiar dentro del runtime:

- `app/`
- `database/`
- `routes/`
- `.env.example`

## Middleware de tenant

En Laravel 13, registrar el alias en `bootstrap/app.php`:

```php
use App\Http\Middleware\ResolveTenant;
use Illuminate\Foundation\Configuration\Middleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'lotox.tenant' => ResolveTenant::class,
    ]);
})
```

## Auth

El modelo autenticable de LotoX es:

```php
App\Models\Profile
```

En `config/auth.php`, el provider API debe apuntar a `Profile`:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\Profile::class,
    ],
],
```

## Sanctum

Instalar y publicar migraciones:

```bash
php artisan install:api
php artisan migrate
php artisan db:seed --class=PlanCatalogSeeder
```

## Prueba rapida

```bash
curl "http://localhost:8000/api/v1/bootstrap?tenant=demo"
```

