@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm bg-mahogany/10 text-mahogany border border-mahogany/30']) }}>
        {{ $status }}
    </div>
@endif
