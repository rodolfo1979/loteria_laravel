<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Models\LotteryDraw;
use App\Services\RoleGate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLotteryDrawController extends Controller
{
    public function store(Request $request, Lottery $lottery, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($lottery->tenant_id === $tenant->id, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'drawTime' => ['nullable', 'date_format:H:i'],
            'timezone' => ['nullable', 'string', 'max:80'],
            'daysOfWeek' => ['nullable', 'array'],
            'status' => ['nullable', 'in:active,paused,closed'],
            'closesBeforeMinutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
        ]);

        $draw = LotteryDraw::query()->create([
            'tenant_id' => $tenant->id,
            'lottery_id' => $lottery->id,
            'name' => $data['name'],
            'draw_time' => $data['drawTime'] ?? null,
            'timezone' => $data['timezone'] ?? 'America/Costa_Rica',
            'days_of_week' => $data['daysOfWeek'] ?? [],
            'status' => $data['status'] ?? 'active',
            'closes_before_minutes' => $data['closesBeforeMinutes'] ?? 10,
        ]);

        return response()->json(['ok' => true, 'draw' => $draw], 201);
    }

    public function update(Request $request, LotteryDraw $draw, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($draw->tenant_id === $tenant->id, 404);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:160'],
            'drawTime' => ['sometimes', 'nullable', 'date_format:H:i'],
            'timezone' => ['sometimes', 'string', 'max:80'],
            'daysOfWeek' => ['sometimes', 'array'],
            'status' => ['sometimes', 'in:active,paused,closed'],
            'closesBeforeMinutes' => ['sometimes', 'integer', 'min:0', 'max:1440'],
        ]);

        $draw->fill([
            'name' => $data['name'] ?? $draw->name,
            'draw_time' => array_key_exists('drawTime', $data) ? $data['drawTime'] : $draw->draw_time,
            'timezone' => $data['timezone'] ?? $draw->timezone,
            'days_of_week' => $data['daysOfWeek'] ?? $draw->days_of_week,
            'status' => $data['status'] ?? $draw->status,
            'closes_before_minutes' => $data['closesBeforeMinutes'] ?? $draw->closes_before_minutes,
        ])->save();

        return response()->json(['ok' => true, 'draw' => $draw]);
    }
}

