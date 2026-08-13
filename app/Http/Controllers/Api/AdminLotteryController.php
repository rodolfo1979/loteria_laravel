<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Services\RoleGate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminLotteryController extends Controller
{
    public function index(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        return response()->json([
            'ok' => true,
            'lotteries' => Lottery::query()
                ->where('tenant_id', $tenant->id)
                ->with(['draws.results' => fn ($query) => $query->latest('result_date')->limit(5)])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'code' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:4'],
            'active' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
        ]);

        $lottery = Lottery::query()->create([
            'tenant_id' => $tenant->id,
            'name' => $data['name'],
            'code' => $data['code'] ?? Str::slug($data['name']),
            'country' => $data['country'] ?? 'CR',
            'active' => $data['active'] ?? true,
            'sort_order' => $data['sortOrder'] ?? 0,
        ]);

        return response()->json(['ok' => true, 'lottery' => $lottery], 201);
    }

    public function update(Request $request, Lottery $lottery, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($lottery->tenant_id === $tenant->id, 404);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:160'],
            'code' => ['sometimes', 'string', 'max:80'],
            'country' => ['sometimes', 'string', 'max:4'],
            'active' => ['sometimes', 'boolean'],
            'sortOrder' => ['sometimes', 'integer', 'min:0'],
        ]);

        $lottery->fill([
            'name' => $data['name'] ?? $lottery->name,
            'code' => $data['code'] ?? $lottery->code,
            'country' => $data['country'] ?? $lottery->country,
            'active' => $data['active'] ?? $lottery->active,
            'sort_order' => $data['sortOrder'] ?? $lottery->sort_order,
        ])->save();

        return response()->json(['ok' => true, 'lottery' => $lottery]);
    }

    public function destroy(Request $request, Lottery $lottery, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($lottery->tenant_id === $tenant->id, 404);

        $lottery->delete();

        return response()->json(['ok' => true]);
    }
}

