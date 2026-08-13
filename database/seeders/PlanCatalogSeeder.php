<?php

namespace Database\Seeders;

use App\Models\PlanCatalog;
use Illuminate\Database\Seeder;

class PlanCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'starter',
                'name' => 'Starter',
                'user_limit' => 100,
                'monthly_price' => 25000,
                'features' => ['private_chat', 'groups', 'image_uploads', 'basic_admin', 'standard_support'],
            ],
            [
                'code' => 'professional',
                'name' => 'Profesional',
                'user_limit' => 300,
                'monthly_price' => 60000,
                'features' => ['private_chat', 'groups', 'image_uploads', 'broadcasts', 'channels', 'priority_support', 'basic_backup'],
            ],
            [
                'code' => 'business',
                'name' => 'Business',
                'user_limit' => 1000,
                'monthly_price' => 120000,
                'features' => ['private_chat', 'groups', 'image_uploads', 'broadcasts', 'channels', 'custom_branding', 'advanced_admin', 'premium_backup'],
            ],
        ];

        foreach ($plans as $plan) {
            PlanCatalog::query()->updateOrCreate(['code' => $plan['code']], $plan);
        }
    }
}

