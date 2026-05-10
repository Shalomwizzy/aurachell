<button {{ $attributes->merge(['type' => 'submit', 'class' => 'auth-btn']) }}>
    {{ $slot }}
</button>
