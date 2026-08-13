<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\AdminChatClear;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Profile;
use App\Services\RoleGate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $profile = $request->user();

        $query = Chat::query()
            ->where('tenant_id', $tenant->id)
            ->with([
                'members.profile',
                'messages' => fn ($query) => $query->latest('created_at')->limit(80),
                'messages.profile',
            ]);

        if ($profile->role === UserRole::Client->value) {
            $query->whereHas('members', fn ($memberQuery) => $memberQuery->where('profile_id', $profile->id));
        }

        $chats = $query->latest('updated_at')->get();

        return response()->json([
            'ok' => true,
            'currentUserId' => $profile->id,
            'chats' => $chats,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $profile = $request->user();

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:160'],
            'memberIds' => ['required', 'array', 'min:1'],
            'memberIds.*' => ['uuid'],
        ]);

        $memberIds = collect($data['memberIds'])
            ->push($profile->id)
            ->unique()
            ->values();

        $validMemberIds = Profile::query()
            ->where('tenant_id', $tenant->id)
            ->whereIn('id', $memberIds)
            ->pluck('id');

        abort_if($validMemberIds->count() !== $memberIds->count(), 422, 'Uno o mas miembros no pertenecen al tenant.');

        $chat = DB::transaction(function () use ($tenant, $profile, $data, $validMemberIds) {
            $chat = Chat::query()->create([
                'tenant_id' => $tenant->id,
                'name' => $data['name'] ?? null,
                'type' => $validMemberIds->count() > 2 ? 'group' : 'direct',
                'created_by' => $profile->id,
            ]);

            foreach ($validMemberIds as $memberId) {
                ChatMember::query()->create([
                    'tenant_id' => $tenant->id,
                    'chat_id' => $chat->id,
                    'profile_id' => $memberId,
                    'role' => $memberId === $profile->id ? 'owner' : 'member',
                ]);
            }

            return $chat->load(['members.profile', 'messages.profile']);
        });

        return response()->json(['ok' => true, 'chat' => $chat], 201);
    }

    public function clear(Request $request, Chat $chat, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        abort_if($chat->tenant_id !== $tenant->id, 404);

        $clear = AdminChatClear::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'chat_id' => $chat->id,
                'admin_id' => $request->user()->id,
            ],
            ['cleared_at' => now()]
        );

        return response()->json(['ok' => true, 'clear' => $clear]);
    }

    public function destroy(Request $request, Chat $chat, RoleGate $gate): JsonResponse
    {
        $gate->requireAdmin($request->user());
        $tenant = $request->attributes->get('tenant');

        abort_if($chat->tenant_id !== $tenant->id, 404);

        $chat->delete();

        return response()->json(['ok' => true]);
    }
}
