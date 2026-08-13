<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\RoleGate;
use App\Services\TenantCapacityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request, RoleGate $gate, TenantCapacityService $capacity): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        return response()->json([
            'ok' => true,
            'usage' => $capacity->usage($tenant),
            'users' => Profile::query()
                ->where('tenant_id', $tenant->id)
                ->where('role', '!=', UserRole::SuperAdmin->value)
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request, RoleGate $gate, TenantCapacityService $capacity): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        $capacity->assertCanAddUser($tenant);

        $data = $request->validate([
            'fullName' => ['required', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'role' => ['nullable', 'in:admin,client'],
            'status' => ['nullable', 'in:pending,approved,blocked'],
        ]);

        $profile = Profile::query()->create([
            'tenant_id' => $tenant->id,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => ! empty($data['password']) ? Hash::make($data['password']) : null,
            'full_name' => $data['fullName'],
            'role' => $data['role'] ?? UserRole::Client->value,
            'status' => $data['status'] ?? UserStatus::Approved->value,
        ]);

        return response()->json(['ok' => true, 'profile' => $profile], 201);
    }

    public function update(Request $request, Profile $user, RoleGate $gate, TenantCapacityService $capacity): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($user->tenant_id === $tenant->id, 404);

        $data = $request->validate([
            'fullName' => ['sometimes', 'string', 'max:160'],
            'adminAlias' => ['sometimes', 'nullable', 'string', 'max:160'],
            'adminTags' => ['sometimes', 'array'],
            'status' => ['sometimes', 'in:pending,approved,blocked'],
        ]);

        if (($data['status'] ?? null) === UserStatus::Approved->value && $user->status === UserStatus::Blocked->value) {
            $capacity->assertCanAddUser($tenant);
        }

        $user->fill([
            'full_name' => $data['fullName'] ?? $user->full_name,
            'admin_alias' => array_key_exists('adminAlias', $data) ? $data['adminAlias'] : $user->admin_alias,
            'admin_tags' => $data['adminTags'] ?? $user->admin_tags,
            'status' => $data['status'] ?? $user->status,
        ])->save();

        return response()->json(['ok' => true, 'profile' => $user]);
    }

    public function destroy(Request $request, Profile $user, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($user->tenant_id === $tenant->id, 404);
        abort_if($user->role === UserRole::Admin->value && $user->id === $request->user()->id, 422, 'No puedes eliminar tu propio admin.');

        $user->delete();

        return response()->json(['ok' => true]);
    }
}

