<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\BankTransferSubmittedMail;
use App\Models\Order;
use App\Services\PaymentGatewaySettingsService;
use App\Traits\SecureFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BankTransferController extends Controller
{
    use SecureFileUpload;
    public function instructions(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('bankTransfer')
            ->firstOrFail();

        // Security: session owner or authenticated user who owns the order
        $sessionOwner = session('last_order_number') === $orderNumber;
        $authOwner    = auth()->check() && auth()->id() === $order->user_id;

        if (!$sessionOwner && !$authOwner) {
            abort(403, 'Unauthorized.');
        }

        $bankDetails  = app(PaymentGatewaySettingsService::class)->getBankDetails();
        $bankTransfer = $order->bankTransfer;

        return view('frontend.bank-transfer.instructions', compact('order', 'bankTransfer', 'bankDetails'));
    }

    public function uploadProof(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('bankTransfer')
            ->firstOrFail();

        $sessionOwner = session('last_order_number') === $orderNumber;
        $authOwner    = auth()->check() && auth()->id() === $order->user_id;

        if (!$sessionOwner && !$authOwner) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'proof'         => 'required|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
            'customer_note' => 'nullable|string|max:500',
        ]);

        $file     = $request->file('proof');
        $ext      = $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp', 'pdf']);
        $filename = 'bt_' . $order->id . '_' . time() . '.' . $ext;
        $dir      = storage_path('app/bank-transfer-proofs');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $filename);

        $order->bankTransfer->update([
            'proof_path'          => $filename,
            'proof_original_name' => $file->getClientOriginalName(),
            'customer_note'       => $request->customer_note,
        ]);

        // Notify admin
        try {
            $adminEmail = config('services.admin.email', config('mail.from.address'));
            Mail::to($adminEmail)->queue(new BankTransferSubmittedMail($order));
        } catch (\Throwable $e) {
            Log::error('BankTransferSubmittedMail failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('order.success', $orderNumber)
            ->with('success', 'Proof of payment uploaded. We will confirm your order within 24 hours.');
    }
}
