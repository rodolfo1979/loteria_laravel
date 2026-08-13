<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Tenant;
use App\Services\RoleGate;
use App\Services\TenantCapacityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminTenantController extends Controller
{
    public function index(Request $request, RoleGate $gate, TenantCapacityService $capacity): JsonResponse
    {
        $gate->requireSuperAdmin($request->user());

        $tenants = Tenant::query()->latest()->get()->map(function (Tenant $tenant) use ($capacity) {
            return [
                ...$tenant->toArray(),
                'usage' => $capacity->usage($tenant),
                'admins' => Profile::query()
                    ->where('tenant_id', $tenant->id)
                    ->where('role', UserRole::Admin->value)
                    ->get(),
            ];
        });

        return response()->json(['ok' => true, 'tenants' => $tenants]);
    }

    public function store(Request $request): JsonResponse
    {
        app(RoleGate::class)->requireSuperAdmin($request->user());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:80'],
            'planCode' => ['required', 'in:starter,professional,business'],
            'extraUserSlots' => ['nullable', 'integer', 'min:0'],
            'adminName' => ['required', 'string', 'max:160'],
            'adminEmail' => ['required', 'email', 'max:255'],
            'adminPassword' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($data) {
            $tenant = Tenant::query()->create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? Str::slug($data['name']),
                'plan_code' => $data['planCode'],
                'extra_user_slots' => $data['extraUserSlots'] ?? 0,
                'billing_status' => 'trial',
                'status' => 'active',
                'plan_started_at' => now(),
            ]);

            $admin = Profile::query()->create([
                'tenant_id' => $tenant->id,
                'email' => $data['adminEmail'],
                'password' => Hash::make($data['adminPassword']),
                'full_name' => $data['adminName'],
                'role' => UserRole::Admin->value,
                'status' => UserStatus::Approved->value,
            ]);

            return compact('tenant', 'admin');
        });

        return response()->json(['ok' => true, ...$result], 201);
    }

    public function update(Request $request, Tenant $tenant, RoleGate $gate): JsonResponse
    {
        $gate->requireSuperAdmin($request->user());

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:160'],
            'status' => ['sometimes', 'in:active,paused,disabled'],
            'billingStatus' => ['sometimes', 'in:trial,active,past_due,cancelled'],
            'planCode' => ['sometimes', 'in:starter,professional,business'],
            'extraUserSlots' => ['sometimes', 'integer', 'min:0'],
            'logoUrl' => ['sometimes', 'nullable', 'string'],
            'primaryColor' => ['sometimes', 'nullable', 'string', 'max:40'],
            'secondaryColor' => ['sometimes', 'nullable', 'string', 'max:40'],
        ]);

        $tenant->fill([
            'name' => $data['name'] ?? $tenant->name,
            'status' => $data['status'] ?? $tenant->status,
            'billing_status' => $data['billingStatus'] ?? $tenant->billing_status,
            'plan_code' => $data['planCode'] ?? $tenant->plan_code,
            'extra_user_slots' => $data['extraUserSlots'] ?? $tenant->extra_user_slots,
            'logo_url' => array_key_exists('logoUrl', $data) ? $data['logoUrl'] : $tenant->logo_url,
            'primary_color' => array_key_exists('primaryColor', $data) ? $data['primaryColor'] : $tenant->primary_color,
            'secondary_color' => array_key_exists('secondaryColor', $data) ? $data['secondaryColor'] : $tenant->secondary_color,
        ])->save();

        return response()->json(['ok' => true, 'tenant' => $tenant]);
    }
}

