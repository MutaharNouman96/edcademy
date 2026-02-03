<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\ChatMessageNotificationMail;

class ChatMessageController extends Controller
{
    //

    public function index()
    {
        $userId = auth()->id();

        $chats = Chat::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['user1', 'user2'])
            ->get();

        $chatId = request('chat') ?? isset($chats[0]->id) ? $chats[0]->id : null;

        return view('chats', compact('chats', 'chatId'));
    }

    public function openChat(User $user)
    {
        $chat = $this->getOrCreateChat($user->id);

        return redirect()->route('chats.index') . '?chat=' . $chat->id;
    }



    public function getOrCreateChat($receiverId)
    {
        $senderId = auth()->id();

        // Check if chat exists in both directions
        $chat = Chat::where(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $senderId)
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->first();

        if (!$chat) {
            $chat = Chat::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
            ]);
        }

        return $chat;
    }

    public function fetchMessages($chatId)
    {
        $messages = ChatMessage::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'nullable|string',
            'type' => 'nullable|string',
            'file' => 'nullable|file',
        ]);

        $chat = Chat::find($request->chat_id);

        // Store file if exists
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat_files');
        }

        $msg = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'type' => $request->type ?? 'text',
            'file' => $filePath,
        ]);

        // Log activity
        $receiver = $chat->sender_id === auth()->id() ? $chat->receiver : $chat->sender;
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            'send_message',
            'ChatMessage',
            $msg->id,
            "Message to {$receiver->full_name}",
            null,
            [
                'message' => $request->message ? substr($request->message, 0, 100) . (strlen($request->message) > 100 ? '...' : '') : 'File attachment',
                'type' => $request->type ?? 'text',
                'receiver_id' => $receiver->id,
                'chat_id' => $chat->id
            ],
            "Sent " . ($request->type ?? 'text') . " message to {$receiver->full_name}",
            ['message_length' => strlen($request->message ?? ''), 'has_file' => !is_null($filePath)]
        );

        // REALTIME PUSHER EVENT
        broadcast(new \App\Events\NewChatMessage($msg))->toOthers();

        // Send email notification to receiver
        try {
            // Only send email if receiver has email notifications enabled (you can add a preference check here)
            EmailService::send(
                $receiver->email,
                new ChatMessageNotificationMail($msg, $receiver),
                'emails'
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send chat message notification email: ' . $e->getMessage());
        }

        return response()->json($msg);
    }
}
