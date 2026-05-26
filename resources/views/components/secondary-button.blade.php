<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 text-xs uppercase tracking-widest font-medium transition disabled:opacity-25', 'style' => 'background:transparent;border:1px solid #371220;color:#371220;border-radius:2px;']) }}>
    {{ $slot }}
</button>
