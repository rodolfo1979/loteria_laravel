<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Tenant;
use App\Services\TenantCapacityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $profile = Profile::query()
            ->where('tenant_id', $tenant->id)
            ->where(function ($query) use ($data) {
                $query->where('email', $data['identifier'])
                    ->orWhere('phone', $data['identifier']);
            })
            ->first();

        if (! $profile || ! $profile->password || ! Hash::check($data['password'], $profile->password)) {
            throw ValidationException::withMessages(['identifier' => 'Credenciales invalidas.']);
        }

        if ($profile->status !== UserStatus::Approved->value) {
            abort(403, 'Tu cuenta esta pendiente o bloqueada.');
        }

        $profile->forceFill(['last_seen_at' => now()])->save();

        return response()->json([
            'ok' => true,
            'token' => $profile->createToken('lotox-mobile')->plainTextToken,
            'profile' => $profile,
            'tenant' => $this->tenantPayload($tenant),
        ]);
    }

    public function register(Request $request, TenantCapacityService $capacity): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $capacity->assertCanAddUser($tenant);

        $data = $request->validate([
            'fullName' => ['required', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        if (empty($data['email']) && empty($data['phone'])) {
            throw ValidationException::withMessages(['identifier' => 'Correo o telefono requerido.']);
        }

        $exists = Profile::query()
            ->where('tenant_id', $tenant->id)
            ->where(function ($query) use ($data) {
                if (! empty($data['email'])) {
                    $query->orWhere('email', $data['email']);
                }
                if (! empty($data['phone'])) {
                    $query->orWhere('phone', $data['phone']);
                }
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages(['identifier' => 'Este usuario ya existe en el negocio.']);
        }

        $profile = Profile::query()->create([
            'tenant_id' => $tenant->id,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'full_name' => $data['fullName'],
            'role' => UserRole::Client->value,
            'status' => UserStatus::Pending->value,
        ]);

        return response()->json([
            'ok' => true,
            'mode' => 'registered',
            'profile' => $profile,
            'message' => 'Cuenta creada. Tu acceso queda pendiente de aprobacion.',
        ], 201);
    }

    public function superAdminLogin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $profile = Profile::query()
            ->where('email', $data['email'])
            ->where('role', UserRole::SuperAdmin->value)
            ->first();

        if (! $profile || ! $profile->password || ! Hash::check($data['password'], $profile->password)) {
            throw ValidationException::withMessages(['email' => 'Credenciales de super admin invalidas.']);
        }

        if ($profile->status !== UserStatus::Approved->value) {
            abort(403, 'El super admin no esta aprobado.');
        }

        return response()->json([
            'ok' => true,
            'token' => $profile->createToken('lotox-super-admin')->plainTextToken,
            'profile' => $profile,
        ]);
    }

    private function tenantPayload(Tenant $tenant): array
    {
        return [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'slug' => $tenant->slug,
            'planCode' => $tenant->plan_code,
            'logoUrl' => $tenant->logo_url,
            'primaryColor' => $tenant->primary_color,
            'secondaryColor' => $tenant->secondary_color,
        ];
    }
}
