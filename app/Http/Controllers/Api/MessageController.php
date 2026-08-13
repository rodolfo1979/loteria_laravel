<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ChatReadMarker;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $profile = $request->user();

        $data = $request->validate([
            'chatId' => ['required', 'uuid'],
            'body' => ['nullable', 'string', 'max:8000'],
            'messageType' => ['nullable', 'in:text,image,file'],
            'attachmentUrl' => ['nullable', 'string'],
            'attachmentName' => ['nullable', 'string', 'max:255'],
        ]);

        $chat = Chat::query()
            ->where('tenant_id', $tenant->id)
            ->where('id', $data['chatId'])
            ->firstOrFail();

        $isMember = $chat->members()->where('profile_id', $profile->id)->exists();
        abort_if(! $isMember && $profile->role === UserRole::Client->value, 403, 'No tienes acceso a este chat.');

        $messageType = $data['messageType'] ?? 'text';
        $body = trim((string) ($data['body'] ?? ''));

        if ($messageType === 'text' && $body === '') {
            abort(422, 'El mensaje no puede estar vacio.');
        }

        if ($messageType !== 'text' && empty($data['attachmentUrl'])) {
            abort(422, 'El adjunto es requerido.');
        }

        $message = Message::query()->create([
            'tenant_id' => $tenant->id,
            'chat_id' => $chat->id,
            'sender_id' => $profile->id,
            'body' => $body ?: null,
            'message_type' => $messageType,
            'attachment_url' => $data['attachmentUrl'] ?? null,
            'attachment_name' => $data['attachmentName'] ?? null,
        ])->load('profile');

        $chat->touch();

        return response()->json(['ok' => true, 'message' => $message], 201);
    }

    public function markRead(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $profile = $request->user();

        $data = $request->validate([
            'chatId' => ['required', 'uuid'],
            'lastReadMessageAt' => ['required', 'date'],
        ]);

        $chat = Chat::query()
            ->where('tenant_id', $tenant->id)
            ->where('id', $data['chatId'])
            ->firstOrFail();

        $isMember = $chat->members()->where('profile_id', $profile->id)->exists();
        abort_if(! $isMember && $profile->role === UserRole::Client->value, 403, 'No tienes acceso a este chat.');

        $marker = ChatReadMarker::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'chat_id' => $chat->id,
                'profile_id' => $profile->id,
            ],
            ['last_read_message_at' => $data['lastReadMessageAt']]
        );

        return response()->json(['ok' => true, 'marker' => $marker]);
    }

    public function destroy(Request $request, Message $message): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $profile = $request->user();

        abort_if($message->tenant_id !== $tenant->id, 404);
        abort_if($message->sender_id !== $profile->id && $profile->role === UserRole::Client->value, 403, 'Solo puedes eliminar tus propios mensajes.');

        $message->delete();

        return response()->json(['ok' => true]);
    }
}
