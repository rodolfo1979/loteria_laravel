<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\TenantCapacityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BootstrapController extends Controller
{
    public function __invoke(Request $request, TenantCapacityService $capacity): JsonResponse
    {
        $slug = $request->query('tenant') ?: $request->header('x-lotox-tenant');
        $tenant = Tenant::query()->where('slug', $slug)->firstOrFail();
        $usage = $capacity->usage($tenant);

        return response()->json([
            'ok' => true,
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'status' => $tenant->status,
                'planCode' => $tenant->plan_code,
                'logoUrl' => $tenant->logo_url,
                'primaryColor' => $tenant->primary_color,
                'secondaryColor' => $tenant->secondary_color,
            ],
            'usage' => [
                'used' => $usage['used'],
                'baseLimit' => $usage['base_limit'],
                'extraUserSlots' => $usage['extra_user_slots'],
                'totalLimit' => $usage['total_limit'],
                'available' => $usage['available'],
            ],
        ]);
    }
}

