<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\GroqChatService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500', 'session_id' => 'required|string']);

        $session = ChatSession::firstOrCreate(
            ['session_id' => $request->session_id],
            ['user_id' => auth()->id(), 'started_at' => now()]
        );

        ChatMessage::create(['chat_session_id' => $session->id, 'role' => 'user', 'content' => $request->message]);

        $history = $session->messages()->latest()->limit(10)->get()->reverse()->map(fn ($m) => [
            'role' => $m->role,
            'content' => $m->content,
        ])->toArray();

        try {
            $reply = app(GroqChatService::class)->sendMessage($history);
        } catch (\Exception) {
            $reply = 'I\'m having trouble right now. Please email us at hello@aurachell.com for assistance.';
        }

        ChatMessage::create(['chat_session_id' => $session->id, 'role' => 'assistant', 'content' => $reply]);

        return response()->json(['reply' => $reply]);
    }
}
