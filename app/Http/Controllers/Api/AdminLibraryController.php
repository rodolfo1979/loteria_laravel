<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use App\Models\QuickReply;
use App\Services\RoleGate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLibraryController extends Controller
{
    public function index(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        return response()->json([
            'ok' => true,
            'quickReplies' => QuickReply::query()->where('tenant_id', $tenant->id)->latest()->get(),
            'mediaLibrary' => MediaLibrary::query()->where('tenant_id', $tenant->id)->latest()->get(),
        ]);
    }

    public function storeQuickReply(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'tag' => ['nullable', 'string', 'max:80'],
            'body' => ['required', 'string', 'max:2000'],
            'tagColor' => ['nullable', 'string', 'max:40'],
            'tagEmoji' => ['nullable', 'string', 'max:20'],
        ]);

        $quickReply = QuickReply::query()->create([
            'tenant_id' => $tenant->id,
            'label' => $data['label'],
            'tag' => $data['tag'] ?? null,
            'body' => $data['body'],
            'tag_color' => $data['tagColor'] ?? null,
            'tag_emoji' => $data['tagEmoji'] ?? null,
        ]);

        return response()->json(['ok' => true, 'quickReply' => $quickReply], 201);
    }

    public function storeMedia(Request $request, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'tag' => ['nullable', 'string', 'max:80'],
            'imageUrl' => ['required', 'string'],
        ]);

        $media = MediaLibrary::query()->create([
            'tenant_id' => $tenant->id,
            'title' => $data['title'],
            'tag' => $data['tag'] ?? null,
            'image_url' => $data['imageUrl'],
        ]);

        return response()->json(['ok' => true, 'media' => $media], 201);
    }

    public function destroyQuickReply(Request $request, QuickReply $quickReply, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        abort_if($quickReply->tenant_id !== $tenant->id, 404);

        $quickReply->delete();

        return response()->json(['ok' => true]);
    }

    public function destroyMedia(Request $request, MediaLibrary $media, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        abort_if($media->tenant_id !== $tenant->id, 404);

        $media->delete();

        return response()->json(['ok' => true]);
    }
}
