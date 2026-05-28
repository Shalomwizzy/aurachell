@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm bg-caramel/10 text-bronze border border-caramel/30']) }}>
        {{ $status }}
    </div>
@endif
