<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReturnRequestMail;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['order', 'user'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $returns = $query->paginate(25)->withQueryString();

        $counts = [
            'all' => ReturnRequest::count(),
            'pending' => ReturnRequest::where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('status', 'approved')->count(),
            'rejected' => ReturnRequest::where('status', 'rejected')->count(),
            'refunded' => ReturnRequest::where('status', 'refunded')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'counts'));
    }

    public function show(ReturnRequest $return)
    {
        $return->load(['order.items.product', 'user']);

        return view('admin.returns.show', compact('return'));
    }

    public function update(Request $request, ReturnRequest $return): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,refunded'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $return->update($data);

        Mail::to($return->user->email)
            ->queue(new ReturnRequestMail($return->load('order'), $data['status']));

        return back()->with('success', 'Return request updated and customer notified.');
    }
}
