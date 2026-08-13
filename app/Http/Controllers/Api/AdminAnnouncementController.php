<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\RoleGate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAnnouncementController extends Controller
{
    public function index(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        return response()->json([
            'ok' => true,
            'announcements' => Announcement::query()->where('tenant_id', $tenant->id)->latest()->paginate(20),
        ]);
    }

    public function store(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:2000'],
            'active' => ['nullable', 'boolean'],
            'startsAt' => ['nullable', 'date'],
            'endsAt' => ['nullable', 'date'],
            'schedule' => ['nullable', 'array'],
        ]);

        $announcement = Announcement::query()->create([
            'tenant_id' => $tenant->id,
            'title' => $data['title'] ?? null,
            'body' => $data['body'],
            'active' => $data['active'] ?? true,
            'starts_at' => $data['startsAt'] ?? null,
            'ends_at' => $data['endsAt'] ?? null,
            'schedule' => $data['schedule'] ?? [],
        ]);

        return response()->json(['ok' => true, 'announcement' => $announcement], 201);
    }
}

