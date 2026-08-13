<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TenantResolver
{
    public function fromRequest(Request $request): Tenant
    {
        $slug = $request->header('x-lotox-tenant')
            ?: $request->query('tenant')
            ?: $request->input('tenant');

        if (! is_string($slug) || trim($slug) === '') {
            throw new NotFoundHttpException('Codigo de negocio requerido.');
        }

        $tenant = Tenant::query()->where('slug', trim($slug))->first();

        if (! $tenant) {
            throw new NotFoundHttpException('El negocio indicado no existe.');
        }

        if ($tenant->status !== 'active') {
            abort(423, 'Este negocio no esta activo.');
        }

        return $tenant;
    }
}

