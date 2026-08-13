<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LotoXDemoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->updateOrCreate(
            ['slug' => 'demo'],
            [
                'name' => 'Demo LotoX',
                'status' => 'active',
                'billing_status' => 'trial',
                'plan_code' => 'starter',
                'extra_user_slots' => 0,
                'plan_started_at' => now(),
                'primary_color' => '#22c55e',
                'secondary_color' => '#38bdf8',
            ]
        );

        Profile::query()->updateOrCreate(
            ['email' => 'superadmin@lotox.local', 'tenant_id' => null],
            [
                'password' => Hash::make('SuperAdmin123'),
                'full_name' => 'Super Admin LotoX',
                'role' => UserRole::SuperAdmin->value,
                'status' => UserStatus::Approved->value,
            ]
        );

        $admin = Profile::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'email' => 'admin@demo.local'],
            [
                'password' => Hash::make('AdminDemo123'),
                'full_name' => 'Admin Demo',
                'role' => UserRole::Admin->value,
                'status' => UserStatus::Approved->value,
            ]
        );

        $client = Profile::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'email' => 'cliente@demo.local'],
            [
                'password' => Hash::make('ClienteDemo123'),
                'full_name' => 'Cliente Demo',
                'role' => UserRole::Client->value,
                'status' => UserStatus::Approved->value,
            ]
        );

        $chat = Chat::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Conversacion demo'],
            ['type' => 'direct', 'created_by' => $admin->id]
        );

        foreach ([$admin, $client] as $member) {
            ChatMember::query()->firstOrCreate(
                ['chat_id' => $chat->id, 'profile_id' => $member->id],
                ['tenant_id' => $tenant->id, 'role' => $member->id === $admin->id ? 'owner' : 'member']
            );
        }

        Message::query()->firstOrCreate(
            ['chat_id' => $chat->id, 'body' => 'Backend Laravel conectado.'],
            [
                'tenant_id' => $tenant->id,
                'sender_id' => $admin->id,
                'message_type' => 'text',
            ]
        );
    }
}

