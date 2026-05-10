<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Order;
use App\Models\Product;
use App\Services\GroqChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        set_time_limit(90); // Groq needs breathing room with full catalog context

        $data = $request->validate(['message' => 'required|string|max:1000']);
        $history = $request->input('history', []);

        $userMessage = trim($data['message']);
        $sessionId = session()->getId();

        $session = null;
        try {
            $session = ChatSession::firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => auth()->id(), 'started_at' => now()]
            );
            ChatMessage::create(['chat_session_id' => $session->id, 'role' => 'user', 'content' => $userMessage]);
        } catch (\Exception) {
        }

        // Detect tracking code / order number and resolve live order data
        $orderContext = $this->resolveOrderContext($userMessage);

        $cleanHistory = (array) $history;

        // When we have a confirmed order, scrub any prior "not found" assistant
        // messages from history so they cannot poison the new response
        if ($orderContext && ! str_starts_with($orderContext, 'No order found')) {
            $cleanHistory = array_values(array_filter($cleanHistory, function ($msg) {
                if (($msg['role'] ?? '') === 'assistant') {
                    $lower = strtolower($msg['content'] ?? '');

                    return ! str_contains($lower, 'not found')
                        && ! str_contains($lower, "doesn't match")
                        && ! str_contains($lower, 'could not find')
                        && ! str_contains($lower, 'not a valid')
                        && ! str_contains($lower, 'not match');
                }

                return true;
            }));
        }

        $messages = array_merge(
            array_slice($cleanHistory, -19),
            [['role' => 'user', 'content' => $userMessage]]
        );

        $catalog = $this->buildCatalog();

        try {
            $reply = app(GroqChatService::class)->sendMessage($messages, $catalog, $orderContext);
        } catch (\Exception) {
            $reply = "I'm having a moment of calm. Please try again shortly, or contact our team at hello@aurachell.com.";
        }

        // Extract add-to-cart action tag if Aura included one
        $cartAction = null;
        if (preg_match('/\[ADD_TO_CART:(\d+):([^:\]]+):(\d+)\]/', $reply, $m)) {
            $cartAction = ['product_id' => (int) $m[1], 'name' => trim($m[2]), 'price' => (int) $m[3]];
            $reply = trim(preg_replace('/\[ADD_TO_CART:[^\]]+\]/', '', $reply));
        }

        try {
            if ($session) {
                ChatMessage::create(['chat_session_id' => $session->id, 'role' => 'assistant', 'content' => $reply]);
            }
        } catch (\Exception) {
        }

        return response()->json(['reply' => $reply, 'cart_action' => $cartAction]);
    }

    private function buildCatalog(): string
    {
        return Cache::remember('chatbot_catalog', 600, function () {
            $products = Product::where('is_active', true)
                ->with('category:id,name')
                ->select('id', 'name', 'price', 'category_id', 'scent_notes', 'stock_quantity', 'capacity_ml')
                ->orderBy('name')
                ->get();

            if ($products->isEmpty()) {
                return 'No products listed yet.';
            }

            return $products->map(function ($p) {
                $stock = $p->stock_quantity > 0 ? '✓' : '✗ OUT OF STOCK';
                $cat = $p->category->name ?? '';
                $price = '₦'.number_format($p->price, 0);
                $scent = $p->scent_notes ?: '';
                $size = $p->capacity_ml ? "{$p->capacity_ml}ml" : '';
                $meta = implode(', ', array_filter([$cat, $size, $scent]));

                return "{$p->name} (ID:{$p->id}) | {$price} | {$stock} | {$meta}";
            })->implode("\n");
        });
    }

    private function resolveOrderContext(string $message): ?string
    {
        // Match AUR-XXXXXXXX tracking codes or AUR-YYYY-NNNNN order numbers
        if (! preg_match('/\b(AUR-[A-Z0-9\-]{5,15})\b/i', $message, $matches)) {
            return null;
        }

        $code = strtoupper($matches[1]);

        $order = Order::where('tracking_code', $code)
            ->orWhere('order_number', $code)
            ->with('items')
            ->first();

        if (! $order) {
            return "No order found with tracking code or order number '{$code}'. Please double-check the code.";
        }

        $statusLabel = ucfirst(str_replace('_', ' ', $order->status));
        $paymentLabel = ucfirst($order->payment_status);
        $itemNames = $order->items->pluck('product_name')->implode(', ');
        $total = '₦'.number_format($order->total, 0);
        $date = $order->created_at->format('d M Y');

        // Explicitly tie the customer's code to the order so the LLM doesn't get confused
        $codeType = $order->tracking_code === $code ? 'Tracking code' : 'Order number';
        $info = "{$codeType} {$code} belongs to Order #{$order->order_number} placed on {$date}. "
              ."Status: {$statusLabel}. Payment: {$paymentLabel}. "
              ."Total: {$total}. Items: {$itemNames}.";

        if ($order->shipped_at) {
            $info .= ' Shipped on: '.$order->shipped_at->format('d M Y').'.';
        }
        if ($order->delivered_at) {
            $info .= ' Delivered on: '.$order->delivered_at->format('d M Y').'.';
        }

        return $info;
    }

    public function history()
    {
        $sessionId = session()->getId();
        $session = ChatSession::where('session_id', $sessionId)->first();

        if (! $session) {
            return response()->json(['messages' => []]);
        }

        $messages = $session->messages()
            ->latest()
            ->limit(20)
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
            ->values();

        return response()->json(['messages' => $messages]);
    }
}
