<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app(TenantResolver::class)->fromRequest($request);

        app()->instance(Tenant::class, $tenant);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}

