<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class ChatLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatSession::with(['user', 'messages'])
            ->withCount('messages')
            ->latest();

        if ($search = $request->input('q')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('session_id', 'like', "%{$search}%");
        }

        $sessions = $query->paginate(30)->withQueryString();
        $total = ChatSession::count();
        $totalMessages = ChatMessage::count();

        return view('admin.chat.index', compact('sessions', 'total', 'totalMessages'));
    }

    public function show($id)
    {
        $session = ChatSession::with(['user', 'messages'])->findOrFail($id);

        return view('admin.chat.show', compact('session'));
    }

    public function destroy($id)
    {
        ChatSession::findOrFail($id)->delete();

        return back()->with('success', 'Chat session deleted.');
    }
}
