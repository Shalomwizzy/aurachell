<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-mahogany border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-mahogany/80 active:bg-mahogany focus:outline-none focus:ring-2 focus:ring-mahogany/50 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
