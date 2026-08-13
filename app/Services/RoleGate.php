<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Profile;

class RoleGate
{
    public function requireAdmin(Profile $profile): void
    {
        abort_unless(
            in_array($profile->role, [UserRole::Admin->value, UserRole::SuperAdmin->value], true),
            403,
            'Acceso de administrador requerido.'
        );
    }

    public function requireSuperAdmin(Profile $profile): void
    {
        abort_unless($profile->role === UserRole::SuperAdmin->value, 403, 'Acceso de super admin requerido.');
    }
}

