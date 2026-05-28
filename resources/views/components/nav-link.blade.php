@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-mahogany text-sm font-medium leading-5 text-text-dark focus:outline-none focus:border-mahogany transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-text-muted hover:text-text-dark hover:border-sand focus:outline-none focus:text-text-dark focus:border-sand transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
