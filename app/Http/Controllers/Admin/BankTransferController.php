<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankTransfer;
use App\Services\PaymentLifecycleService;
use Illuminate\Http\Request;

class BankTransferController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = BankTransfer::with(['order.user'])->latest('submitted_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $transfers = $query->paginate(25)->withQueryString();

        $counts = [
            'all'      => BankTransfer::count(),
            'pending'  => BankTransfer::where('status', 'pending')->count(),
            'approved' => BankTransfer::where('status', 'approved')->count(),
            'rejected' => BankTransfer::where('status', 'rejected')->count(),
        ];

        return view('admin.bank-transfers.index', compact('transfers', 'counts', 'status'));
    }

    public function show(BankTransfer $transfer)
    {
        $transfer->load(['order.user', 'order.items.product', 'reviewer']);
        return view('admin.bank-transfers.show', compact('transfer'));
    }

    public function approve(Request $request, BankTransfer $transfer)
    {
        abort_if($transfer->status !== 'pending', 422, 'This transfer has already been reviewed.');

        app(PaymentLifecycleService::class)->markBankTransferApproved(
            $transfer->order->load('items.product'),
            $transfer,
            auth()->id()
        );

        return back()->with('success', 'Payment approved. Order has been marked as paid.');
    }

    public function reject(Request $request, BankTransfer $transfer)
    {
        abort_if($transfer->status !== 'pending', 422, 'This transfer has already been reviewed.');

        $request->validate(['admin_note' => 'nullable|string|max:500']);

        app(PaymentLifecycleService::class)->markBankTransferRejected(
            $transfer->order,
            $transfer,
            auth()->id(),
            $request->admin_note ?? ''
        );

        return back()->with('success', 'Transfer rejected. Customer has been notified.');
    }

    public function downloadProof(BankTransfer $transfer)
    {
        abort_if(!$transfer->proof_path, 404, 'No proof uploaded yet.');

        $path = storage_path('app/bank-transfer-proofs/' . $transfer->proof_path);
        abort_unless(file_exists($path), 404, 'Proof file not found on server.');

        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="' . ($transfer->proof_original_name ?? $transfer->proof_path) . '"',
        ]);
    }
}
