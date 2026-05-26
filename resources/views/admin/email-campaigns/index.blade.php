@extends('layouts.admin')
@section('title', 'Email Campaigns')
@section('breadcrumb', 'Marketing')

@section('content')
<div class="p-6 lg:p-8 max-w-4xl">

    <div class="mb-8">
        <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Email Campaigns</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Send targeted emails to your customers and subscribers. Each campaign is sent immediately when you click the button.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm flex items-center gap-3"
         style="background:var(--adm-success-bg);border:1px solid var(--adm-success-fg);color:var(--adm-success-fg);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 px-4 py-3 text-sm flex items-center gap-3"
         style="background:var(--adm-danger-bg);border:1px solid var(--adm-danger-fg);color:var(--adm-danger-fg);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="space-y-5">

        {{-- New Product Alert --}}
        <div class="adm-card p-6">
            <div class="flex items-start justify-between gap-6 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-sm flex items-center justify-center text-lg flex-shrink-0"
                             style="background:rgba(55,18,32,0.15);">🆕</div>
                        <div>
                            <h3 class="text-sm font-semibold" style="color:var(--adm-text-strong);">New Product Alert</h3>
                            <p class="text-xs" style="color:var(--adm-muted);">Sent to all registered users & newsletter subscribers</p>
                        </div>
                    </div>
                    <p class="text-xs ml-12" style="color:var(--adm-muted);">
                        Announces a new product with its name, image, price, and a direct shop link. Send this whenever you publish a new product.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.email-campaigns.new-product') }}" class="flex items-end gap-3 flex-shrink-0 flex-wrap">
                    @csrf
                    <div>
                        <label class="block text-[10px] tracking-widest uppercase mb-1.5" style="color:var(--adm-muted);">Select Product</label>
                        <select name="product_id" required
                                style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-text);padding:8px 12px;font-size:13px;min-width:200px;">
                            <option value="">Choose a product…</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            onclick="return confirm('Send new product alert to all users and subscribers?')"
                            class="px-5 py-2 text-xs tracking-widest uppercase font-medium transition-colors flex items-center gap-2"
                            style="background:var(--adm-accent);color:#FAF5ED;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Now
                    </button>
                </form>
            </div>
        </div>

        {{-- Wishlist Reminder --}}
        <div class="adm-card p-6">
            <div class="flex items-start justify-between gap-6 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-sm flex items-center justify-center text-lg flex-shrink-0"
                             style="background:rgba(55,18,32,0.15);">❤️</div>
                        <div>
                            <h3 class="text-sm font-semibold" style="color:var(--adm-text-strong);">Wishlist Reminder</h3>
                            <p class="text-xs" style="color:var(--adm-muted);">Sent to users with saved wishlist items · Auto-scheduled every Monday 9am</p>
                        </div>
                    </div>
                    <p class="text-xs ml-12" style="color:var(--adm-muted);">
                        Reminds customers that their wishlisted products are still available. Each email shows up to 3 saved items with a direct link back to their wishlist.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.email-campaigns.wishlist-reminder') }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Send wishlist reminders now to all users with wishlist items?')"
                            class="px-5 py-2 text-xs tracking-widest uppercase font-medium transition-colors flex items-center gap-2"
                            style="background:var(--adm-accent);color:#FAF5ED;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Now
                    </button>
                </form>
            </div>
        </div>

        {{-- Cart Abandonment --}}
        <div class="adm-card p-6">
            <div class="flex items-start justify-between gap-6 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-sm flex items-center justify-center text-lg flex-shrink-0"
                             style="background:rgba(55,18,32,0.15);">🛒</div>
                        <div>
                            <h3 class="text-sm font-semibold" style="color:var(--adm-text-strong);">Cart Abandonment Reminder</h3>
                            <p class="text-xs" style="color:var(--adm-muted);">Sent to users with carts abandoned 24+ hours ago · Auto-scheduled daily at 10am</p>
                        </div>
                    </div>
                    <p class="text-xs ml-12" style="color:var(--adm-muted);">
                        Gently reminds customers who left items in their cart. Shows the abandoned items and links directly back to their cart to complete the purchase.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.email-campaigns.cart-reminder') }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Send cart abandonment reminders now?')"
                            class="px-5 py-2 text-xs tracking-widest uppercase font-medium transition-colors flex items-center gap-2"
                            style="background:var(--adm-accent);color:#FAF5ED;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Now
                    </button>
                </form>
            </div>
        </div>

        {{-- Festive Campaign --}}
        <div class="adm-card p-6">
            <div class="flex items-start justify-between gap-6 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-sm flex items-center justify-center text-lg flex-shrink-0"
                             style="background:rgba(55,18,32,0.15);">🎉</div>
                        <div>
                            <h3 class="text-sm font-semibold" style="color:var(--adm-text-strong);">Festive Campaign</h3>
                            <p class="text-xs" style="color:var(--adm-muted);">Sent to all users & subscribers · Manual trigger per event</p>
                        </div>
                    </div>
                    <p class="text-xs ml-12" style="color:var(--adm-muted);">
                        Seasonal greetings email for major holidays — Christmas, Easter, Eid, Ramadan, or New Year. Select the event and send.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.email-campaigns.festive') }}" class="flex items-end gap-3 flex-shrink-0 flex-wrap">
                    @csrf
                    <div>
                        <label class="block text-[10px] tracking-widest uppercase mb-1.5" style="color:var(--adm-muted);">Select Event</label>
                        <select name="event" required
                                style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-text);padding:8px 12px;font-size:13px;min-width:180px;">
                            <option value="christmas">Christmas</option>
                            <option value="easter">Easter</option>
                            <option value="eid">Eid</option>
                            <option value="ramadan">Ramadan</option>
                            <option value="new_year">New Year</option>
                        </select>
                    </div>
                    <button type="submit"
                            onclick="return confirm('Send festive email to all users and subscribers?')"
                            class="px-5 py-2 text-xs tracking-widest uppercase font-medium transition-colors flex items-center gap-2"
                            style="background:var(--adm-accent);color:#FAF5ED;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Now
                    </button>
                </form>
            </div>
        </div>

        {{-- New Month --}}
        <div class="adm-card p-6">
            <div class="flex items-start justify-between gap-6 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-sm flex items-center justify-center text-lg flex-shrink-0"
                             style="background:rgba(55,18,32,0.15);">🗓️</div>
                        <div>
                            <h3 class="text-sm font-semibold" style="color:var(--adm-text-strong);">Happy New Month</h3>
                            <p class="text-xs" style="color:var(--adm-muted);">Sent to all users & subscribers · Auto-scheduled 1st of each month at 8am</p>
                        </div>
                    </div>
                    <p class="text-xs ml-12" style="color:var(--adm-muted);">
                        A warm monthly greeting featuring your current highlighted products. The system auto-sends this on the 1st — use this button to send manually at any time.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.email-campaigns.new-month') }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Send happy new month email to all users and subscribers?')"
                            class="px-5 py-2 text-xs tracking-widest uppercase font-medium transition-colors flex items-center gap-2"
                            style="background:var(--adm-accent);color:#FAF5ED;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Now
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- Info box --}}
    <div class="mt-8 p-4 text-sm flex items-start gap-3" style="background:var(--adm-info-bg);border:1px solid var(--adm-info-fg);color:var(--adm-info-fg);">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p>The <strong>Wishlist Reminder</strong>, <strong>Cart Abandonment</strong>, and <strong>New Month</strong> emails are also sent automatically on schedule. Use these buttons to trigger them manually outside of their normal schedule.</p>
    </div>

</div>
@endsection
