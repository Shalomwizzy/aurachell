<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->get('unread') === '1') {
            $query->where('is_read', false);
        }

        $messages = $query->paginate(20)->withQueryString();
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function markRead(ContactMessage $message)
    {
        $message->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Marked as read.');
    }

    public function reply(Request $request, ContactMessage $message)
    {
        $data = $request->validate([
            'reply_subject' => 'required|string|max:200',
            'reply_body' => 'required|string|max:5000',
        ]);

        $fromName = Setting::get('store_name', 'Aurachell');
        $fromEmail = Setting::get('store_email', config('mail.from.address'));

        Mail::send([], [], function ($mail) use ($data, $message, $fromName, $fromEmail) {
            $mail->to($message->email, $message->name)
                ->from($fromEmail, $fromName)
                ->replyTo($fromEmail, $fromName)
                ->subject($data['reply_subject'])
                ->html(nl2br(e($data['reply_body'])));
        });

        $message->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', "Reply sent to {$message->name} ({$message->email}).");
    }
}
