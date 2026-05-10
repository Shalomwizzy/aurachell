<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterBroadcastMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($search = $request->input('q')) {
            $query->where('email', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
        }

        $subscribers = $query->latest()->paginate(50)->withQueryString();
        $total = NewsletterSubscriber::count();

        return view('admin.newsletter.index', compact('subscribers', 'total'));
    }

    public function broadcast(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:200',
            'body' => 'required|string|max:10000',
        ]);

        $subscribers = NewsletterSubscriber::all();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'No subscribers to send to.');
        }

        $sent = 0;
        foreach ($subscribers as $sub) {
            Mail::to($sub->email)->send(
                new NewsletterBroadcastMail($data['subject'], $data['body'], $sub->name ?? '')
            );
            $sent++;
        }

        return back()->with('success', "Newsletter sent to {$sent} subscriber".($sent !== 1 ? 's' : '').'.');
    }

    public function export(): StreamedResponse
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->get();

        return response()->streamDownload(function () use ($subscribers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'Name', 'Subscribed At']);
            foreach ($subscribers as $s) {
                fputcsv($out, [$s->email, $s->name ?? '', $s->created_at->format('Y-m-d H:i')]);
            }
            fclose($out);
        }, 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
