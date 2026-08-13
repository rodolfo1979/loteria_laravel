<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LotteryDraw;
use App\Models\LotteryResult;
use App\Services\RoleGate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLotteryResultController extends Controller
{
    public function store(Request $request, LotteryDraw $draw, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');
        abort_unless($draw->tenant_id === $tenant->id, 404);

        $data = $request->validate([
            'resultDate' => ['required', 'date'],
            'winningNumbers' => ['required', 'array', 'min:1'],
            'source' => ['nullable', 'string', 'max:160'],
        ]);

        $result = LotteryResult::query()->updateOrCreate(
            ['lottery_draw_id' => $draw->id, 'result_date' => $data['resultDate']],
            [
                'tenant_id' => $tenant->id,
                'winning_numbers' => $data['winningNumbers'],
                'source' => $data['source'] ?? 'admin',
                'published_at' => now(),
            ]
        );

        return response()->json(['ok' => true, 'result' => $result]);
    }
}

