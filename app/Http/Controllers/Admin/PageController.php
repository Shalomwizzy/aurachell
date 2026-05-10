<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private array $pages = [
        'about' => 'About Us',
        'faq' => 'FAQ',
        'shipping_returns' => 'Shipping & Returns',
        'privacy_policy' => 'Privacy Policy',
        'terms' => 'Terms of Service',
        'cookie_policy' => 'Cookie Policy',
    ];

    public function index()
    {
        $settings = [];
        foreach (array_keys($this->pages) as $key) {
            $settings['page_'.$key] = Setting::get('page_'.$key, $this->defaults()[$key]);
        }

        return view('admin.pages.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach (array_keys($this->pages) as $key) {
            Setting::set('page_'.$key, $request->input('page_'.$key, ''));
        }

        return back()->with('success', 'Page content saved successfully.');
    }

    private function defaults(): array
    {
        return [
            'about' => "ABOUT AURACHELL\n\nAurachell was born from a simple belief: that your home should feel like a sanctuary.\n\nWe create luxury ceramic, glass, and reed diffusers using the finest fragrance oils — crafted to fill your space with warmth, calm, and beauty. Every product is designed in-house, blending timeless aesthetics with long-lasting scent performance.\n\nBased in Lagos, Nigeria, we believe in slow living, intentional spaces, and the quiet power of scent to shape how you feel.\n\nOUR PROMISE\n\nEvery Aurachell product is made with care, quality materials, and a genuine love for what we do. We stand behind every bottle, and we're here to help you find the perfect scent for your space.",

            'faq' => "FREQUENTLY ASKED QUESTIONS\n\nQ: How do I use a reed diffuser?\nA: Place the reeds in the bottle and allow 24 hours for the fragrance to travel up the reeds. Flip the reeds every 1–2 weeks for a stronger scent.\n\nQ: How long does a diffuser last?\nA: Our standard diffusers last 2–3 months depending on room size and reed count. Travel diffusers last approximately 4–6 weeks.\n\nQ: Can I reorder the same scent?\nA: Yes! All our scents are available year-round. You can shop by scent name directly in our collection.\n\nQ: Do you offer gift wrapping?\nA: Yes, all orders are beautifully packaged. For special occasions, contact us and we'll add a personalised note.\n\nQ: How do I track my order?\nA: You can track your order using your tracking code (starts with AUR-) on the Track Order page, or log in to your account to view order status.\n\nQ: What is your return policy?\nA: We accept returns within 7 days of delivery for unopened, undamaged items. Please see our Shipping & Returns page for full details.\n\nQ: Do you ship outside Lagos?\nA: Yes, we ship across Nigeria. Delivery times and fees vary by state — see our Shipping & Returns page for details.",

            'shipping_returns' => "SHIPPING & DELIVERY\n\nLagos Delivery\nOrders within Lagos are delivered within 1–3 business days.\n\nOther States\nDelivery to other states takes 3–7 business days depending on your location.\n\nFree Delivery\nEnjoy free delivery on all orders over ₦20,000.\n\nOrder Processing\nOrders are processed within 24 hours of payment confirmation. You will receive an email confirmation and tracking code once your order is dispatched.\n\n---\n\nRETURNS & REFUNDS\n\nWe want you to love your Aurachell purchase. If you're not satisfied, here's what you need to know:\n\nEligibility\nReturns are accepted within 7 days of delivery for items that are:\n- Unopened and in original packaging\n- Undamaged and unused\n\nHow to Return\nContact us at hello@aurachell.com with your order number and reason for return. We'll guide you through the process.\n\nRefunds\nOnce we receive and inspect the returned item, your refund will be processed within 5–7 business days to your original payment method.\n\nExceptions\nWe cannot accept returns on opened or used diffusers for hygiene reasons. Damaged items caused by the customer are not eligible for return.",

            'privacy_policy' => "PRIVACY POLICY\n\nLast updated: January 2025\n\nAt Aurachell, we take your privacy seriously. This policy explains what information we collect, how we use it, and how we protect it.\n\nINFORMATION WE COLLECT\n\nWhen you place an order or create an account, we collect:\n- Your name, email address, and phone number\n- Your delivery and billing address\n- Payment information (processed securely — we do not store card details)\n- Your order history\n\nWhen you browse our website, we may collect:\n- Anonymised analytics data (pages visited, time on site)\n- Cookie data to improve your experience\n\nHOW WE USE YOUR INFORMATION\n\nWe use your information to:\n- Process and fulfil your orders\n- Send order confirmations and delivery updates\n- Send promotional emails (only with your consent)\n- Improve our products and website\n- Provide customer support\n\nWE DO NOT:\n- Sell or share your personal data with third parties for marketing purposes\n- Store your payment card details\n\nDATA SECURITY\n\nAll data is stored securely. Payments are processed by Paystack, a certified payment gateway. We use encryption and industry-standard security measures.\n\nYOUR RIGHTS\n\nYou may request access to, correction of, or deletion of your personal data at any time by emailing hello@aurachell.com.\n\nCONTACT\n\nFor any privacy-related questions, contact us at hello@aurachell.com.",

            'terms' => "TERMS OF SERVICE\n\nLast updated: January 2025\n\nBy using the Aurachell website and placing an order, you agree to the following terms.\n\nORDERS & PAYMENT\n\nAll orders are subject to availability and payment confirmation. We reserve the right to refuse or cancel any order.\n\nPrices are listed in Nigerian Naira (₦) and are subject to change without notice. Prices at the time of your order will be honoured.\n\nPayment must be completed in full before an order is processed.\n\nPRODUCTS\n\nProduct images are for illustration purposes and may vary slightly from actual items. We strive to represent all products accurately.\n\nSHIPPING\n\nDelivery times are estimates and not guaranteed. Aurachell is not responsible for delays caused by courier services or unforeseen circumstances.\n\nREFUSAL OF SERVICE\n\nWe reserve the right to refuse service, cancel accounts, or remove orders at our discretion.\n\nINTELLECTUAL PROPERTY\n\nAll content on this website — including product images, descriptions, logos, and brand assets — is the property of Aurachell and may not be reproduced without written permission.\n\nLIMITATION OF LIABILITY\n\nAurachell shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or website.\n\nCHANGES TO TERMS\n\nWe may update these terms at any time. Continued use of the website constitutes acceptance of the updated terms.\n\nCONTACT\n\nFor questions about these terms, please contact us at hello@aurachell.com.",

            'cookie_policy' => "COOKIE POLICY\n\nLast updated: May 2025\n\nThis Cookie Policy explains how Aurachell uses cookies and similar technologies when you visit our website.\n\nWHAT ARE COOKIES?\n\nCookies are small text files stored on your device when you visit a website. They help the website remember your preferences, keep you logged in, and understand how you use the site.\n\nWHAT COOKIES WE USE\n\nEssential Cookies\nThese are necessary for the website to function. They include:\n- Session cookies to keep you logged in\n- Cart cookies to remember items in your basket\n- Security tokens to prevent fraud\n\nYou cannot opt out of essential cookies as they are required for the site to work.\n\nAnalytics Cookies\nWith your consent, we use analytics tools to understand how visitors use our website. This data is anonymised and used only to improve the website.\n\nMarketing Cookies (Google Analytics)\nWith your consent, we use Google Analytics to track website traffic.\n\nHOW TO MANAGE COOKIES\n\nYou can accept or decline non-essential cookies using the cookie banner when you first visit the site. You can change your preference at any time using the Cookie Settings link in the footer or visiting this page.\n\nCONTACT\n\nFor any questions about our use of cookies, contact us at hello@aurachell.com.",
        ];
    }
}
