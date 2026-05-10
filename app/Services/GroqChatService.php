<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqChatService
{
    private string $basePrompt = "You are Aura, the personal shopping assistant for Aurachell — a luxury home diffuser brand in Nigeria. Prices are in Nigerian Naira (₦).\n\nYOUR TWO JOBS:\n1. Help customers find their perfect diffuser through friendly conversation.\n2. Answer any question about the brand, products, orders, shipping, and returns.\n\nRECOMMENDATION FLOW — when someone wants help choosing or seems unsure, ask 1–2 of these questions naturally (never all at once):\n• Scent strength: light & subtle / medium & balanced / bold & intense?\n• Mood: calming & peaceful / energising & fresh / warm & romantic?\n• Room: bedroom / living room / office / small bathroom?\n• Budget: under ₦10,000 / ₦10–20k / above ₦20,000?\n\nAfter 2–3 answers, recommend 1–3 products from the CATALOG. Format each as:\n✦ [Name] — ₦[Price]: [one sentence why it matches their preferences]\n\nOnly recommend IN STOCK (✓) products. Never recommend OUT OF STOCK items.\n\nADD TO CART — when the customer clearly wants to add a specific product (says \"add to cart\", \"I'll take it\", \"I want that\", \"order that\", \"buy that\", or similar buying intent for one product), append on a NEW LINE at the very end of your reply this exact tag:\n[ADD_TO_CART:PRODUCT_ID:Product Name:NumericPrice]\nUse the ID from (ID:X) in the catalog. Example: [ADD_TO_CART:2:Lavender Dreams Ceramic Diffuser:24000]\nOnly add this tag when they want to buy — never when browsing or asking questions.\n\nORDER TRACKING — if a [LIVE ORDER LOOKUP] block appears in the system context, use it as ground truth. If it says the order was confirmed, give the customer that status confidently. If it says no order was found, apologise and suggest they double-check their code from their confirmation email. Never tell a customer their AUR- code is the wrong format. If no code has been provided yet, ask for their AUR- tracking code or order number.\n\nABOUT AURACHELL:\n- Luxury reed, ceramic, and glass diffusers handcrafted in Nigeria\n- Shipping: Lagos 1–3 days, other states 3–7 days; free delivery over ₦20,000\n- Returns: accepted within 7 days, unopened items only\n- Contact: hello@aurachell.com\n- Payment: Paystack (secure, card/transfer)\n- Customer portal: account dashboard, order history, wishlist, reviews\n- Blog/Journal: fragrance tips and guides on the website\n- Track orders at /track-order with your AUR- code or in your account\n\nKeep replies warm, concise, and on-brand. If they need human help: hello@aurachell.com. Don't answer questions unrelated to home fragrance, Aurachell, or their order.";

    public function sendMessage(array $messages, string $catalog = '', ?string $orderContext = null): string
    {
        // Order context goes at the TOP so it has highest priority over everything else
        $orderBlock = '';
        if ($orderContext !== null) {
            if (str_starts_with($orderContext, 'No order found')) {
                $orderBlock = "IMPORTANT — ORDER LOOKUP RESULT: No order was found with the code the customer provided. Tell them kindly you could not locate it and suggest they re-check their confirmation email. Do NOT say the code is wrong format or invalid.\n\n";
            } else {
                $orderBlock = "CRITICAL — CONFIRMED ORDER DATA FROM DATABASE: {$orderContext} This tracking code IS valid and this order EXISTS in the system. Your response must be to give the customer this order status. Do NOT say the code is invalid, unrecognised, or doesn't match any format — it is registered and confirmed.\n\n";
            }
        }

        $systemContent = $orderBlock.$this->basePrompt;

        if ($catalog) {
            $systemContent .= "\n\nPRODUCT CATALOG (Name | Price | Stock | Category, Size, Scent notes):\n".$catalog;
        }

        $payload = [
            'model' => config('services.groq.model', 'llama-3.3-70b-versatile'),
            'messages' => array_merge(
                [['role' => 'system', 'content' => $systemContent]],
                array_slice($messages, -10)
            ),
            'temperature' => 0.7,
            'max_tokens' => 500,
        ];

        $response = Http::withToken(config('services.groq.key'))
            ->timeout(60)
            ->connectTimeout(10)
            ->post(config('services.groq.url', 'https://api.groq.com/openai/v1/chat/completions'), $payload);

        if ($response->failed()) {
            throw new \RuntimeException('Groq API error '.$response->status().': '.$response->body());
        }

        return $response->json('choices.0.message.content', 'Sorry, I could not process your message. Please try again.');
    }
}
