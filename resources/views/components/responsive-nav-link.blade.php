@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-mahogany text-start text-base font-medium text-mahogany bg-mahogany/5 focus:outline-none focus:text-mahogany focus:bg-mahogany/10 focus:border-mahogany transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-text-dark/60 hover:text-text-dark hover:bg-sand/10 hover:border-sand focus:outline-none focus:text-text-dark focus:bg-sand/10 focus:border-sand transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
