<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function getConversations(Request $request)
    {
        $user = $request->user();

        $conversations = ChatMessage::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) use ($user) {
                $otherUser = $messages->first()->sender_id === $user->id
                    ? $messages->first()->receiver
                    : $messages->first()->sender;

                return [
                    'user' => $otherUser,
                    'last_message' => $messages->first(),
                    'unread_count' => $messages->where('receiver_id', $user->id)
                        ->where('is_read', false)
                        ->count()
                ];
            });

        return response()->json($conversations);
    }

    public function getMessages(Request $request, $userId)
    {
        $messages = ChatMessage::where(function ($query) use ($request, $userId) {
            $query->where('sender_id', $request->user()->id)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($request, $userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $request->user()->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240' // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ];

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat-attachments', 'public');
            $data['attachment'] = $path;
        }

        $message = ChatMessage::create($data);

        // Broadcast the message event
        broadcast(new \App\Events\NewChatMessage($message))->toOthers();

        return response()->json($message->load(['sender', 'receiver']));
    }

    public function markAsRead(Request $request, $messageId)
    {
        $message = ChatMessage::where('receiver_id', $request->user()->id)
            ->findOrFail($messageId);

        $message->update(['is_read' => true]);

        return response()->json(['message' => 'Message marked as read']);
    }
}
