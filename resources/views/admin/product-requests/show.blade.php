@extends('layouts.admin')
@section('title', 'Product Request — ' . $productRequest->product_name)

@section('content')
<div class="p-6 lg:p-8 max-w-4xl">

    {{-- Back --}}
    <a href="{{ route('admin.product-requests.index') }}"
       class="inline-flex items-center gap-2 text-xs text-text-muted hover:text-[var(--adm-gold)] transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Requests
    </a>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 bg-mahogany/20 border border-mahogany/25 text-mahogany text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Request Details --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6">
                <div class="flex items-start justify-between mb-5">
                    <h1 class="text-white text-lg font-medium">{{ $productRequest->product_name }}</h1>
                    @php $colors = ['pending' => 'text-mahogany bg-mahogany/12 border-mahogany/20', 'viewed' => 'text-mahogany bg-sand/12 border-sand/20', 'fulfilled' => 'text-mahogany bg-mahogany/12 border-mahogany/25']; @endphp
                    <span class="px-3 py-1 text-[10px] uppercase tracking-wider border {{ $colors[$productRequest->status] }}">{{ $productRequest->status }}</span>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-text-muted text-[10px] uppercase tracking-widest mb-1">Customer</p>
                            <p class="text-white">{{ $productRequest->customer_name }}</p>
                            <p class="text-text-muted">{{ $productRequest->customer_email }}</p>
                        </div>
                        <div>
                            <p class="text-text-muted text-[10px] uppercase tracking-widest mb-1">Date Submitted</p>
                            <p class="text-white">{{ $productRequest->created_at->format('d M Y, g:ia') }}</p>
                        </div>
                        @if($productRequest->scent_preference)
                        <div>
                            <p class="text-text-muted text-[10px] uppercase tracking-widest mb-1">Scent Preference</p>
                            <p class="text-white">{{ $productRequest->scent_preference }}</p>
                        </div>
                        @endif
                        @if($productRequest->budget)
                        <div>
                            <p class="text-text-muted text-[10px] uppercase tracking-widest mb-1">Budget</p>
                            <p class="text-white">{{ $productRequest->budget }}</p>
                        </div>
                        @endif
                    </div>

                    @if($productRequest->description)
                    <div class="pt-4 border-t border-[rgba(55,18,32,0.10)]">
                        <p class="text-text-muted text-[10px] uppercase tracking-widest mb-2">Description</p>
                        <p class="text-warmSand-300 leading-relaxed">{{ $productRequest->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Reference Image --}}
            @if($productRequest->image_path)
            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6">
                <p class="text-text-muted text-[10px] uppercase tracking-widest mb-4">Reference Image</p>
                <img src="{{ $productRequest->image_url }}" alt="Reference"
                     class="max-w-full max-h-80 object-contain border border-[rgba(55,18,32,0.10)]">
            </div>
            @endif

        </div>

        {{-- Admin Actions --}}
        <div class="space-y-4">

            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6">
                <h2 class="text-sm font-medium text-white mb-5">Update Status</h2>

                <form action="{{ route('admin.product-requests.update', $productRequest) }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-text-muted mb-2">Status</label>
                        <select name="status" class="admin-input w-full">
                            @foreach(['pending' => 'Pending', 'viewed' => 'Viewed', 'fulfilled' => 'Fulfilled'] as $val => $label)
                            <option value="{{ $val }}" {{ $productRequest->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-text-muted mb-2">Note to Customer <span class="normal-case tracking-normal text-[var(--adm-muted)] text-xs">(optional)</span></label>
                        <textarea name="admin_note" rows="4"
                                  class="admin-input w-full resize-none"
                                  placeholder="e.g. We are sourcing this item and will notify you soon...">{{ $productRequest->admin_note }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 text-xs uppercase tracking-widest bg-[var(--adm-gold)] text-[rgba(55,18,32,0.95)] font-semibold hover:opacity-90 transition-opacity">
                        Save Changes
                    </button>
                </form>
            </div>

            {{-- Delete --}}
            <form action="{{ route('admin.product-requests.destroy', $productRequest) }}" method="POST"
                  onsubmit="return confirm('Delete this request permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2.5 text-xs uppercase tracking-widest border border-mahogany/30 text-mahogany hover:bg-mahogany/15 transition-colors">
                    Delete Request
                </button>
            </form>

        </div>
    </div>

</div>
@endsection
