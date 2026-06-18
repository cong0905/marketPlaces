<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-primary border border-transparent rounded-lg font-label-md text-label-md text-on-primary hover:opacity-90 active:translate-y-[2px] active:shadow-none transition-all shadow-[0_2px_0_0_#6b4900] focus:outline-none']) }}>
    {{ $slot }}
</button>
