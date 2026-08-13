<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\PlanCatalog;
use App\Models\Profile;
use App\Models\Tenant;
use RuntimeException;

class TenantCapacityService
{
    public function usage(Tenant $tenant): array
    {
        $plan = PlanCatalog::query()->findOrFail($tenant->plan_code);
        $used = Profile::query()
            ->where('tenant_id', $tenant->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where('role', '!=', UserRole::SuperAdmin->value)
            ->count();

        $baseLimit = (int) $plan->user_limit;
        $extra = max(0, (int) $tenant->extra_user_slots);
        $total = $baseLimit + $extra;

        return [
            'plan' => $plan,
            'used' => $used,
            'base_limit' => $baseLimit,
            'extra_user_slots' => $extra,
            'total_limit' => $total,
            'available' => max(0, $total - $used),
        ];
    }

    public function assertCanAddUser(Tenant $tenant): void
    {
        $usage = $this->usage($tenant);

        if ($usage['used'] >= $usage['total_limit']) {
            $plan = $usage['plan'];
            throw new RuntimeException(
                "Limite de usuarios alcanzado para el plan {$plan->name}: {$usage['used']}/{$usage['total_limit']}. Contacta al Super Admin para ampliar el plan o comprar usuarios extra."
            );
        }
    }
}

