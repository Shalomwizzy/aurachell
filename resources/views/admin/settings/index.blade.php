@extends('layouts.admin')
@section('title', 'Settings')
@section('breadcrumb', 'System')

@section('content')
<div class="p-6 lg:p-8 max-w-5xl">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Site Settings</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Configure your store's global options</p>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm rounded-sm flex items-center gap-3"
         style="background:var(--adm-success-bg);border:1px solid var(--adm-success-fg);color:var(--adm-success-fg);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="space-y-6">

            {{-- Tabs --}}
            <div class="flex gap-1 border-b mb-6 overflow-x-auto" style="border-color:var(--adm-border);">
                @foreach([
                    'general'    => 'General',
                    'shipping'   => 'Shipping',
                    'products'   => 'Products',
                    'appearance' => 'Appearance',
                    'payments'   => 'Payments',
                    'social'     => 'Social',
                    'seo'        => 'SEO',
                    'returns'    => 'Returns',
                    'tracking'   => 'Ads & Pixels',
                ] as $key=>$label)
                <button type="button" id="adm-stab-btn-{{ $key }}" onclick="admSetTab('{{ $key }}')"
                    class="adm-tab px-4 py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-colors -mb-px whitespace-nowrap">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- ============================== GENERAL ============================== --}}
            <div id="adm-stab-general" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="adm-label">Store Name *</label>
                        <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'Aurachell' }}" required class="adm-input">
                        @error('store_name')<p class="text-mahogany text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="adm-label">Store Email *</label>
                        <input type="email" name="store_email" value="{{ $settings['store_email'] ?? '' }}" required class="adm-input">
                    </div>
                    <div>
                        <label class="adm-label">Phone</label>
                        <input type="text" name="store_phone" value="{{ $settings['store_phone'] ?? '' }}" class="adm-input">
                    </div>
                    <div>
                        <label class="adm-label">Currency *</label>
                        <input type="text" name="currency" value="{{ $settings['currency'] ?? '₦' }}" required class="adm-input">
                    </div>
                </div>
                <div>
                    <label class="adm-label">Store Address</label>
                    <input type="text" name="store_address" value="{{ $settings['store_address'] ?? '' }}" class="adm-input">
                </div>
                <div class="adm-card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Announcement Bar</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Displayed at the top of every page</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="announcement_active" value="1"
                                   {{ ($settings['announcement_active'] ?? '0') === '1' ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-10 h-5 rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"
                                 style="background:rgba(55,18,32,0.20);"></div>
                        </label>
                    </div>
                    <input type="text" name="announcement_bar" value="{{ $settings['announcement_bar'] ?? '' }}"
                           placeholder="e.g. Free shipping on orders over ₦20,000"
                           class="adm-input">
                </div>
            </div>

            {{-- ============================== SHIPPING ============================== --}}
            <div id="adm-stab-shipping" class="space-y-6" style="display:none;">
                <div class="adm-card p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded flex items-center justify-center" style="background:rgba(55,18,32,0.25);">
                            <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Shipping</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Delivery rates are managed per zone</p>
                        </div>
                    </div>
                    <div class="p-4 rounded text-sm" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.2);color:var(--adm-muted);">
                        Shipping rates are now configured per Nigerian zone (Lagos, South-West, North, etc.) with separate standard and express prices and free-shipping thresholds per zone.
                        <a href="{{ route('admin.shipping.index') }}" style="color:var(--adm-gold);" class="ml-1 underline">Manage Shipping Zones →</a>
                    </div>
                    <div class="mt-5">
                        <label class="adm-label">Estimated Delivery Time (display text)</label>
                        <input type="text" name="delivery_time_estimate" value="{{ $settings['delivery_time_estimate'] ?? '2–5 business days' }}" placeholder="e.g. 2–5 business days" class="adm-input">
                        <p class="text-xs mt-1" style="color:var(--adm-muted);">Shown on product pages and order emails</p>
                    </div>
                </div>
            </div>

            {{-- ============================== PRODUCTS ============================== --}}
            <div id="adm-stab-products" class="space-y-6" style="display:none;">
                <div class="adm-card p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded flex items-center justify-center" style="background:rgba(55,18,32,0.25);">
                            <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Catalog Defaults</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Inventory thresholds and product display</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="adm-label">Low Stock Alert Threshold</label>
                            <input type="number" min="0" name="low_stock_threshold" value="{{ $settings['low_stock_threshold'] ?? '5' }}" class="adm-input">
                            <p class="text-xs mt-1" style="color:var(--adm-muted);">Show "low stock" warning when ≤ this number</p>
                        </div>
                        <div>
                            <label class="adm-label">Out-of-Stock Behaviour</label>
                            <select name="out_of_stock_behavior" class="adm-input">
                                <option value="hide" {{ ($settings['out_of_stock_behavior'] ?? '') === 'hide' ? 'selected' : '' }}>Hide from shop</option>
                                <option value="show" {{ ($settings['out_of_stock_behavior'] ?? 'show') === 'show' ? 'selected' : '' }}>Show with "Sold Out" label</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Products Per Page (Shop)</label>
                            <input type="number" min="6" max="60" name="products_per_page" value="{{ $settings['products_per_page'] ?? '12' }}" class="adm-input">
                        </div>
                        <div>
                            <label class="adm-label">Default Sort Order</label>
                            <select name="default_sort" class="adm-input">
                                <option value="featured" {{ ($settings['default_sort'] ?? 'featured') === 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="newest" {{ ($settings['default_sort'] ?? '') === 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_asc" {{ ($settings['default_sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ ($settings['default_sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================== APPEARANCE ============================== --}}
            <div id="adm-stab-appearance" class="space-y-6" style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="adm-label mb-4">Logo</label>
                        @if($logo = ($settings['logo'] ?? null))
                        <div class="mb-4 p-4 inline-block adm-card">
                            <img src="{{ asset('images/' . $logo) }}" alt="Logo" class="h-12">
                        </div>
                        @endif
                        <div class="border border-dashed p-6 text-center transition-colors cursor-pointer relative rounded"
                             style="border-color:var(--adm-border);"
                             onmouseover="this.style.borderColor='var(--adm-gold)'" onmouseout="this.style.borderColor='var(--adm-border)'">
                            <input type="file" name="logo" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            <svg class="w-8 h-8 mx-auto mb-2" style="color:var(--adm-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs" style="color:var(--adm-muted);">Click to upload logo</p>
                            <p class="text-[10px] mt-1" style="color:var(--adm-muted);opacity:0.7;">PNG, JPG, SVG, WEBP · Max 2MB</p>
                        </div>
                    </div>
                    <div>
                        <label class="adm-label mb-4">Favicon</label>
                        @if($fav = ($settings['favicon'] ?? null))
                        <div class="mb-4 p-4 inline-block adm-card">
                            <img src="{{ asset('images/' . $fav) }}" alt="Favicon" class="h-8">
                        </div>
                        @endif
                        <div class="border border-dashed p-6 text-center transition-colors cursor-pointer relative rounded"
                             style="border-color:var(--adm-border);"
                             onmouseover="this.style.borderColor='var(--adm-gold)'" onmouseout="this.style.borderColor='var(--adm-border)'">
                            <input type="file" name="favicon" accept="image/*,.ico" class="absolute inset-0 opacity-0 cursor-pointer">
                            <svg class="w-8 h-8 mx-auto mb-2" style="color:var(--adm-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs" style="color:var(--adm-muted);">Click to upload favicon</p>
                            <p class="text-[10px] mt-1" style="color:var(--adm-muted);opacity:0.7;">ICO or PNG · Max 512KB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ PAYMENTS TAB ═══ --}}
            <div id="adm-stab-payments" class="space-y-6" style="display:none;">

                {{-- Gateway Toggles --}}
                <div class="adm-card p-6 mb-6">
                    <h3 class="text-sm font-semibold mb-1" style="color:var(--adm-text);">Payment Methods</h3>
                    <p class="text-xs mb-5" style="color:var(--adm-muted);">Choose which payment methods are available to customers at checkout.</p>
                    <div class="space-y-4">
                        @foreach([
                            'paystack'      => ['label'=>'Paystack',      'desc'=>'Card payments via Paystack'],
                            'flutterwave'   => ['label'=>'Flutterwave',   'desc'=>'Card & mobile money via Flutterwave'],
                            'bank_transfer' => ['label'=>'Bank Transfer', 'desc'=>'Manual bank transfer with proof upload'],
                        ] as $key => $info)
                        <div class="flex items-center justify-between py-3 border-b" style="border-color:var(--adm-border);">
                            <div>
                                <p class="text-sm font-medium" style="color:var(--adm-text);">{{ $info['label'] }}</p>
                                <p class="text-xs" style="color:var(--adm-muted);">{{ $info['desc'] }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_{{ $key }}_enabled" value="1"
                                       {{ (\App\Models\Setting::get("payment_{$key}_enabled", '0') === '1') ? 'checked' : '' }}>
                                <span class="text-xs ml-3" style="color:var(--adm-muted);">
                                    {{ (\App\Models\Setting::get("payment_{$key}_enabled", '0') === '1') ? 'Enabled' : 'Disabled' }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Paystack --}}
                <div class="adm-card p-6 mb-6">
                    <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Paystack Credentials</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="adm-label">Public Key</label>
                            <input type="text" name="paystack_public_key"
                                   value="{{ \App\Models\Setting::get('paystack_public_key') ?? config('paystack.publicKey') }}"
                                   placeholder="pk_test_xxxxxxxx" class="adm-input w-full">
                        </div>
                        <div>
                            <label class="adm-label">Secret Key</label>
                            <input type="password" name="paystack_secret_key"
                                   value="{{ \App\Models\Setting::get('paystack_secret_key') ?? '' }}"
                                   placeholder="sk_test_xxxxxxxx" class="adm-input w-full">
                        </div>
                        <p class="text-xs" style="color:var(--adm-muted);opacity:0.8;">Note: keys stored here are saved to the database. To take effect in Paystack's SDK, also set them in your <code style="background:rgba(55,18,32,0.10);padding:2px 6px;border-radius:3px;">.env</code> file.</p>
                    </div>
                </div>

                {{-- Flutterwave --}}
                <div class="adm-card p-6 mb-6">
                    <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Flutterwave Credentials</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="adm-label">Public Key</label>
                            <input type="text" name="flutterwave_public_key"
                                   value="{{ \App\Models\Setting::get('flutterwave_public_key') }}"
                                   placeholder="FLWPUBK_TEST-xxxxxxxx-X" class="adm-input w-full">
                        </div>
                        <div>
                            <label class="adm-label">Secret Key</label>
                            <input type="password" name="flutterwave_secret_key"
                                   value="{{ \App\Models\Setting::get('flutterwave_secret_key') }}"
                                   placeholder="FLWSECK_TEST-xxxxxxxx-X" class="adm-input w-full">
                        </div>
                        <div>
                            <label class="adm-label">Webhook Hash</label>
                            <input type="password" name="flutterwave_webhook_hash"
                                   value="{{ \App\Models\Setting::get('flutterwave_webhook_hash') }}"
                                   placeholder="Your Flutterwave webhook secret hash" class="adm-input w-full">
                        </div>
                        <p class="text-xs" style="color:var(--adm-muted);">Also add these as <code style="background:rgba(55,18,32,0.10);padding:1px 5px;border-radius:3px;">FLUTTERWAVE_PUBLIC_KEY</code>, <code style="background:rgba(55,18,32,0.10);padding:1px 5px;border-radius:3px;">FLUTTERWAVE_SECRET_KEY</code>, <code style="background:rgba(55,18,32,0.10);padding:1px 5px;border-radius:3px;">FLUTTERWAVE_WEBHOOK_HASH</code> in your server .env file.</p>
                    </div>
                </div>

                {{-- Bank Transfer --}}
                <div class="adm-card p-6">
                    <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Bank Transfer Details</h3>
                    <p class="text-xs mb-4" style="color:var(--adm-muted);">These details are shown to customers when they select Bank Transfer at checkout.</p>
                    <div class="space-y-4">
                        <div>
                            <label class="adm-label">Bank Name</label>
                            <input type="text" name="bank_transfer_bank_name"
                                   value="{{ \App\Models\Setting::get('bank_transfer_bank_name') }}"
                                   placeholder="e.g. First Bank of Nigeria" class="adm-input w-full">
                        </div>
                        <div>
                            <label class="adm-label">Account Name</label>
                            <input type="text" name="bank_transfer_account_name"
                                   value="{{ \App\Models\Setting::get('bank_transfer_account_name') }}"
                                   placeholder="e.g. Aurachell Limited" class="adm-input w-full">
                        </div>
                        <div>
                            <label class="adm-label">Account Number</label>
                            <input type="text" name="bank_transfer_account_number"
                                   value="{{ \App\Models\Setting::get('bank_transfer_account_number') }}"
                                   placeholder="0123456789" class="adm-input w-full">
                        </div>
                        <div>
                            <label class="adm-label">Instructions <span style="color:var(--adm-muted);">(shown to customer)</span></label>
                            <textarea name="bank_transfer_instructions" rows="3"
                                      placeholder="e.g. Please transfer the exact order amount and upload your receipt below."
                                      class="adm-input w-full resize-none">{{ \App\Models\Setting::get('bank_transfer_instructions') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ============================== SOCIAL ============================== --}}
            <div id="adm-stab-social" class="space-y-6" style="display:none;">
                <p class="text-xs" style="color:var(--adm-muted);">Enter the URL for each platform you want to display. Use the toggle to show or hide each icon in your website footer.</p>

                {{-- WhatsApp --}}
                <div class="adm-card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="adm-label mb-0">WhatsApp Number</label>
                        <div class="flex items-center gap-2">
                            <span class="text-xs" style="color:var(--adm-muted);">Show in footer</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_social_whatsapp" value="1"
                                       {{ ($settings['show_social_whatsapp'] ?? '1') !== '0' ? 'checked' : '' }} class="sr-only peer">
                                <div class="relative w-9 h-5 rounded-full transition-colors duration-200 peer-checked:bg-mahogany after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 peer-checked:after:translate-x-4"
                                     style="background:var(--adm-border);"></div>
                            </label>
                        </div>
                    </div>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" placeholder="+2348012345678" class="adm-input">
                    <p class="text-xs mt-1.5" style="color:var(--adm-muted);">Also enables the WhatsApp chat button on your storefront.</p>
                </div>

                @foreach(['instagram'=>'Instagram','facebook'=>'Facebook','twitter'=>'Twitter / X','tiktok'=>'TikTok'] as $key=>$label)
                <div class="adm-card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="adm-label mb-0">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <span class="text-xs" style="color:var(--adm-muted);">Show in footer</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_social_{{ $key }}" value="1"
                                       {{ ($settings['show_social_' . $key] ?? '1') !== '0' ? 'checked' : '' }} class="sr-only peer">
                                <div class="relative w-9 h-5 rounded-full transition-colors duration-200 peer-checked:bg-mahogany after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 peer-checked:after:translate-x-4"
                                     style="background:var(--adm-border);"></div>
                            </label>
                        </div>
                    </div>
                    <input type="url" name="{{ $key }}_url" value="{{ $settings[$key . '_url'] ?? '' }}" placeholder="https://..." class="adm-input">
                </div>
                @endforeach
            </div>

            {{-- ============================== SEO ============================== --}}
            <div id="adm-stab-seo" class="space-y-5" style="display:none;">
                <div>
                    <label class="adm-label">Default Meta Title <span class="text-[10px] normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(max 70 chars)</span></label>
                    <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" maxlength="70" class="adm-input">
                </div>
                <div>
                    <label class="adm-label">Default Meta Description <span class="text-[10px] normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(max 160 chars)</span></label>
                    <textarea name="meta_description" rows="3" maxlength="160" class="adm-input resize-none">{{ $settings['meta_description'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="adm-label">Google Analytics ID</label>
                    <input type="text" name="ga_measurement_id"
                           value="{{ $settings['ga_measurement_id'] ?? config('app.google_analytics_id', '') }}"
                           placeholder="G-XXXXXXXXXX" class="adm-input font-mono">
                    <p class="text-xs mt-1" style="color:var(--adm-muted);">Overrides the <code style="background:rgba(55,18,32,0.10);padding:1px 5px;border-radius:3px;">GOOGLE_ANALYTICS_ID</code> env value once saved here.</p>
                </div>
            </div>

            {{-- ============================== RETURNS ============================== --}}
            <div id="adm-stab-returns" class="space-y-6" style="display:none;">
                <div class="adm-card p-6 space-y-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded flex items-center justify-center" style="background:rgba(55,18,32,0.25);">
                            <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Return Policy Window</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">How long customers have to submit a return request after delivery</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="adm-label">Return Window (days)</label>
                            <input type="number" name="return_window_days" min="1" max="90"
                                   value="{{ $settings['return_window_days'] ?? 3 }}" class="adm-input">
                            <p class="text-xs mt-1" style="color:var(--adm-muted);">Customers can request a return within this many days after delivery. Set to 0 to disable returns.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================== TRACKING ============================== --}}
            <div id="adm-stab-tracking" class="space-y-6" style="display:none;">

                {{-- What is Facebook Pixel? --}}
                <div class="p-5 rounded-sm space-y-3" style="background:rgba(55,18,32,0.06);border:1px solid rgba(55,18,32,0.20);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background:rgba(55,18,32,0.15);">
                            <svg class="w-4 h-4" fill="#C9A96F" viewBox="0 0 24 24"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.313 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
                        </div>
                        <p class="text-sm font-medium" style="color:var(--adm-text);">What is Facebook Pixel?</p>
                    </div>
                    <p class="text-xs leading-relaxed" style="color:var(--adm-muted);">
                        Facebook Pixel is a small piece of tracking code that connects your Aurachell store to your Facebook and Instagram ad account. Once active, Facebook can see who visits your store and what they purchase — this lets you:
                    </p>
                    <ul class="text-xs space-y-1.5 pl-4" style="color:var(--adm-muted);list-style:disc;">
                        <li><strong style="color:var(--adm-text);">Retarget visitors</strong> — show ads to people who browsed your products but didn't buy</li>
                        <li><strong style="color:var(--adm-text);">Find lookalike audiences</strong> — reach new customers who look like your existing buyers</li>
                        <li><strong style="color:var(--adm-text);">Measure ad performance</strong> — see exactly which ads led to a purchase, not just a click</li>
                        <li><strong style="color:var(--adm-text);">Optimise campaigns</strong> — Meta's algorithm learns from real purchase data to spend your budget smarter</li>
                    </ul>
                    <p class="text-xs" style="color:var(--adm-muted);opacity:0.8;">
                        Events tracked automatically: <code style="background:rgba(55,18,32,0.12);padding:1px 5px;border-radius:3px;">PageView</code> on every page · <code style="background:rgba(55,18,32,0.12);padding:1px 5px;border-radius:3px;">ViewContent</code> on product pages · <code style="background:rgba(55,18,32,0.12);padding:1px 5px;border-radius:3px;">Purchase</code> after order confirmation.
                    </p>
                </div>

                {{-- Pixel ID + Toggle --}}
                <div class="adm-card p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Enable Facebook Pixel</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Turn off to pause tracking without deleting your Pixel ID</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="facebook_pixel_enabled" value="1"
                                   {{ ($settings['facebook_pixel_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="relative w-10 h-5 rounded-full transition-colors duration-200 peer-checked:bg-mahogany after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 peer-checked:after:translate-x-5"
                                 style="background:var(--adm-border);"></div>
                        </label>
                    </div>
                    <div>
                        <label class="adm-label">Facebook Pixel ID</label>
                        <input type="text" name="facebook_pixel_id"
                               value="{{ $settings['facebook_pixel_id'] ?? '' }}"
                               placeholder="e.g. 1234567890123456"
                               pattern="\d{10,20}"
                               inputmode="numeric"
                               class="adm-input font-mono">
                        <p class="text-xs mt-1.5" style="color:var(--adm-muted);">
                            Find your Pixel ID in
                            <span style="color:var(--adm-gold);">Meta Business Suite → Events Manager → Your Pixel → Settings</span>.
                            It's a 15–16 digit number.
                        </p>
                    </div>
                    <div class="p-3 rounded-sm text-xs" style="background:var(--adm-surface-alt);color:var(--adm-muted);">
                        <strong style="color:var(--adm-text);">Privacy note:</strong> Pixel fires only after a visitor accepts cookies via the cookie consent banner. Visitors who decline will not be tracked. This keeps you compliant with NDPR and GDPR.
                    </div>
                </div>

            </div>

            {{-- Save --}}
            <div class="pt-6 border-t flex justify-end gap-3" style="border-color:var(--adm-border);">
                <button type="submit" class="adm-btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
</div>

<style>
    .adm-label {
        display: block;
        font-size: 10px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--adm-muted);
        margin-bottom: 8px;
        font-weight: 500;
    }
    .adm-input {
        width: 100%;
        background: var(--adm-surface-alt);
        border: 1px solid var(--adm-border);
        padding: 10px 14px;
        font-size: 13px;
        color: var(--adm-text);
        border-radius: 4px;
        transition: border-color .15s, box-shadow .15s;
    }
    .adm-input:focus {
        outline: none;
        border-color: var(--adm-gold);
        box-shadow: 0 0 0 1px var(--adm-gold);
    }
    .adm-tab {
        color: var(--adm-muted);
        border-bottom: 2px solid transparent;
    }
    .adm-tab:hover { color: var(--adm-text); }
    .adm-tab.tab-active {
        color: var(--adm-gold);
        border-bottom-color: var(--adm-gold);
    }
    .adm-btn-primary {
        padding: 12px 32px;
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        font-weight: 500;
        background: var(--adm-accent);
        color: #FFFFFF;
        border-radius: 4px;
        transition: opacity .15s, transform .05s;
    }
    .adm-btn-primary:hover { opacity: 0.92; }
    .adm-btn-primary:active { transform: scale(0.98); }
    .adm-light .adm-btn-primary { color: #FFFFFF; }
</style>
<script>
(function(){
    var TABS = ['general','shipping','products','appearance','payments','social','seo','returns','tracking'];
    function admSetTab(t) {
        TABS.forEach(function(k) {
            var panel = document.getElementById('adm-stab-' + k);
            var btn   = document.getElementById('adm-stab-btn-' + k);
            if (panel) panel.style.display = (k === t) ? '' : 'none';
            if (btn)   btn.classList.toggle('tab-active', k === t);
        });
        localStorage.setItem('adm-settings-tab', t);
    }
    window.admSetTab = admSetTab;
    // restore saved tab or default to general
    admSetTab(localStorage.getItem('adm-settings-tab') || 'general');
})();
</script>
@endsection
